<?php

namespace App\Http\Controllers;

use App\Models\BurialPermit;
use App\Models\SmsNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsController extends Controller
{
    /**
     * POST /permits/{permit}/sms
     * Manually send an SMS notification for a permit.
     */
    public function send(Request $request, BurialPermit $permit)
    {
        $request->validate([
            'message_type'   => 'required|in:expiry_warning,approved,released,custom',
            'custom_message' => 'nullable|string|max:160',
        ]);

        $contact = $permit->applicant_contact;

        if (!$contact) {
            return redirect()->route('permits.show', $permit)
                ->withErrors(['sms' => 'No contact number on record for this permit.']);
        }

        $message = $this->buildMessage($request->message_type, $permit, $request->custom_message);
        $result  = $this->sendViaVonage($contact, $message);

        SmsNotification::create([
            'permit_id'        => $permit->id,
            'recipient_number' => $contact,
            'message'          => $message,
            'status'           => $result['success'] ? 'sent' : 'failed',
            'type'             => $request->message_type,
            'sent_at'          => $result['success'] ? now() : null,
        ]);

        if ($result['success']) {
            return redirect()->route('permits.show', $permit)
                ->with('success', 'SMS sent to ' . $contact . ' successfully.');
        }

        return redirect()->route('permits.show', $permit)
            ->withErrors(['sms' => 'SMS failed to send: ' . $result['error']]);
    }

    /**
     * Called by the scheduler — sends expiry warnings in bulk.
     */
    public function sendExpiryWarningsBulk(int $daysAhead = 30): array
    {
        $permits = BurialPermit::where('status', 'released')
            ->whereNotNull('expiry_date')
            ->whereNotNull('applicant_contact')
            ->whereDate('expiry_date', '>=', now()->toDateString())
            ->whereDate('expiry_date', '<=', now()->addDays($daysAhead)->toDateString())
            ->whereDoesntHave('smsNotifications', function ($q) {
                $q->where('type', 'expiry_warning')
                  ->where('status', 'sent')
                  ->where('sent_at', '>=', now()->subDays(7));
            })
            ->with('deceased')
            ->get();

        $sent   = 0;
        $failed = 0;

        foreach ($permits as $permit) {
            $message = $this->buildMessage('expiry_warning', $permit);
            $result  = $this->sendViaVonage($permit->applicant_contact, $message);

            SmsNotification::create([
                'permit_id'        => $permit->id,
                'recipient_number' => $permit->applicant_contact,
                'message'          => $message,
                'status'           => $result['success'] ? 'sent' : 'failed',
                'type'             => 'expiry_warning',
                'sent_at'          => $result['success'] ? now() : null,
            ]);

            $result['success'] ? $sent++ : $failed++;
        }

        Log::info("SMS Expiry Warnings: {$sent} sent, {$failed} failed.");

        return ['sent' => $sent, 'failed' => $failed, 'total' => $permits->count()];
    }

    // ──────────────────────────────────────────────────
    // Vonage SMS sender
    // ──────────────────────────────────────────────────

    private function sendViaVonage(string $number, string $message): array
    {
        $apiKey    = config('services.vonage.api_key');
        $apiSecret = config('services.vonage.api_secret');
        $from      = config('services.vonage.from', 'LGUCarmen');

        // Normalise PH number: 09XXXXXXXXX → 639XXXXXXXXX
        $number = $this->normalisePhNumber($number);

        try {
            $response = Http::timeout(10)->post('https://rest.nexmo.com/sms/json', [
                'api_key'    => $apiKey,
                'api_secret' => $apiSecret,
                'to'         => $number,
                'from'       => $from,
                'text'       => $message,
            ]);

            $data = $response->json();

            // Vonage returns a messages array — check status 0 = success
            $status = $data['messages'][0]['status'] ?? '1';

            if ($response->successful() && $status === '0') {
                return ['success' => true];
            }

            $errorText = $data['messages'][0]['error-text'] ?? 'Unknown error';
            Log::error("Vonage SMS failed: " . $errorText);
            return ['success' => false, 'error' => $errorText];

        } catch (\Throwable $e) {
            Log::error("Vonage SMS exception: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    // ──────────────────────────────────────────────────
    // Message builder
    // ──────────────────────────────────────────────────

    private function buildMessage(string $type, BurialPermit $permit, ?string $custom = null): string
    {
        $deceased = optional($permit->deceased);
        $name     = trim($deceased->first_name . ' ' . $deceased->last_name);
        $permitNo = $permit->permit_number;
        $expiry   = $permit->expiry_date?->format('M d, Y') ?? 'N/A';
        $daysLeft = $permit->expiry_date ? now()->diffInDays($permit->expiry_date, false) : null;

        return match ($type) {
            'expiry_warning' => "LGU Carmen: Your burial permit {$permitNo} for {$name} expires on {$expiry}" .
                                ($daysLeft !== null ? " ({$daysLeft} days left)" : '') .
                                ". Please renew at the Municipal Civil Registrar office.",

            'approved'  => "LGU Carmen: Burial permit {$permitNo} for {$name} has been APPROVED. " .
                           "Please visit the office for release.",

            'released'  => "LGU Carmen: Burial permit {$permitNo} for {$name} has been RELEASED. " .
                           "Expiry: {$expiry}. Keep this for your records.",

            'custom'    => $custom ?? "LGU Carmen: Update regarding permit {$permitNo}.",

            default     => "LGU Carmen: Notification for permit {$permitNo}.",
        };
    }

    // ──────────────────────────────────────────────────
    // PH number normaliser
    // ──────────────────────────────────────────────────

    private function normalisePhNumber(string $number): string
    {
        // Strip non-numeric chars
        $number = preg_replace('/\D/', '', $number);

        // 09XXXXXXXXX → 639XXXXXXXXX
        if (str_starts_with($number, '09') && strlen($number) === 11) {
            return '63' . substr($number, 1);
        }

        // Already international format
        if (str_starts_with($number, '63') && strlen($number) === 12) {
            return $number;
        }

        return $number;
    }
}
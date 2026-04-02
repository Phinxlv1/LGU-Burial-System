<?php

namespace App\Services;

use App\Models\BurialPermit;
use App\Models\DeceasedPerson;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BurialPermitService
{
    /**
     * Generate a unique, SQLite-compatible permit number.
     */
    public function generatePermitNumber(): string
    {
        $year = now()->year;
        
        // Find the highest sequence number for the current year
        $lastPermit = BurialPermit::whereYear('created_at', $year)
            ->orderBy('permit_number', 'desc')
            ->get()
            ->sortByDesc(function ($p) {
                $parts = explode('-', $p->permit_number);
                return (int) ($parts[2] ?? 0);
            })
            ->first();

        $nextNumber = $lastPermit
            ? (int) explode('-', $lastPermit->permit_number)[2] + 1
            : 1;

        do {
            $permitNumber = 'BP-' . $year . '-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
            $nextNumber++;
        } while (BurialPermit::where('permit_number', $permitNumber)->exists());

        return $permitNumber;
    }

    /**
     * Check for duplicate or highly similar deceased persons to prevent duplicate permits.
     */
    public function checkDuplicate(array $data): ?DeceasedPerson
    {
        $fName  = trim(strtolower($data['first_name']));
        $lName  = trim(strtolower($data['last_name']));
        $mName  = trim(strtolower($data['middle_name'] ?? ''));
        $suffix = trim(strtolower($data['name_extension'] ?? ''));

        return DeceasedPerson::whereRaw('LOWER(trim(first_name)) = ?', [$fName])
            ->whereRaw('LOWER(trim(last_name)) = ?', [$lName])
            ->where(function($q) use ($mName) {
                if ($mName !== '') {
                    $q->whereRaw('LOWER(trim(middle_name)) = ?', [$mName]);
                } else {
                    $q->whereNull('middle_name')->orWhereRaw('trim(middle_name) = ""');
                }
            })
            ->where(function($q) use ($suffix) {
                if ($suffix !== '') {
                    $q->whereRaw('LOWER(trim(name_extension)) = ?', [$suffix]);
                } else {
                    $q->whereNull('name_extension')->orWhereRaw('trim(name_extension) = ""');
                }
            })
            ->first();
    }

    /**
     * Check for fuzzy name match (First + Last) to alert potential duplicates.
     */
    public function checkSimilar(string $first, string $last): ?DeceasedPerson
    {
        return DeceasedPerson::whereRaw('LOWER(trim(first_name)) = ?', [trim(strtolower($first))])
            ->whereRaw('LOWER(trim(last_name)) = ?', [trim(strtolower($last))])
            ->first();
    }

    /**
     * Renew an expiring or expired permit.
     */
    public function renewPermit(BurialPermit $permit, int $yearsToAdd = 5): BurialPermit
    {
        return DB::transaction(function () use ($permit, $yearsToAdd) {
            $oldValues = $permit->only(['status', 'expiry_date', 'remarks']);
            $newExpiry = now()->addYears($yearsToAdd);

            $permit->update([
                'status'        => 'active',
                'expiry_date'   => $newExpiry,
                'processed_by'  => Auth::id(),
                'renewal_count' => ($permit->renewal_count ?? 0) + 1,
                'remarks'       => 'Last renewed on ' . now()->format('F d, Y') . ' by ' . Auth::user()->name,
            ]);

            ActivityLog::record(
                action: 'renewed',
                modelType: 'BurialPermit',
                modelId: $permit->id,
                modelLabel: $permit->permit_number,
                oldValues: $oldValues,
                newValues: [
                    'status'      => 'active',
                    'expiry_date' => $newExpiry->toDateString(),
                    'remarks'     => $permit->fresh()->remarks,
                ],
                description: "Permit {$permit->permit_number} renewed until " . $newExpiry->format('F d, Y')
            );

            return $permit;
        });
    }

    /**
     * Load application settings.
     */
    public function getSettings(): array
    {
        $path = storage_path('app/settings.json');
        if (! file_exists($path)) {
            return [];
        }
        return json_decode(file_get_contents($path), true) ?? [];
    }
}

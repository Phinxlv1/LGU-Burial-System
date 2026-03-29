<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\BurialPermit;
use App\Models\DeceasedPerson;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BurialPermitController extends Controller
{
    public function index(Request $request)
    {
        $sort      = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc') === 'desc' ? 'desc' : 'asc';

        $query = BurialPermit::with('deceased');

        if ($sort === 'last_name') {
            $query->orderByRaw("(SELECT last_name FROM deceased_persons WHERE deceased_persons.id = burial_permits.deceased_id) {$direction}");
        } elseif ($sort === 'date_of_death') {
            $query->orderByRaw("(SELECT date_of_death FROM deceased_persons WHERE deceased_persons.id = burial_permits.deceased_id) {$direction}");
        } elseif ($sort === 'status') {
    $query->orderByRaw("
        CASE
            WHEN expiry_date IS NOT NULL AND expiry_date < NOW() AND renewal_count = 0 THEN 1
            WHEN expiry_date IS NOT NULL AND expiry_date <= DATE_ADD(NOW(), INTERVAL 30 DAY)
                 AND expiry_date > NOW() AND renewal_count = 0 THEN 2
            WHEN status = 'released' OR status = 'active' THEN 3
            ELSE 4
        END {$direction}, permit_number ASC
    ");
        } elseif (in_array($sort, ['permit_number', 'permit_type', 'created_at', 'renewal_count'])) {
            $query->orderBy($sort, $direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $permits = $query->get();

        $sortUrl = fn (string $col) => request()->fullUrlWithQuery([
            'sort'      => $col,
            'direction' => (request()->get('sort', 'status') === $col && request()->get('direction', 'asc') === 'asc') ? 'desc' : 'asc',
            'page'      => 1,
        ]);

        $sortIcon = fn (string $col) => request()->get('sort', 'status') === $col
            ? '<span class="sort-icon '.request()->get('direction', 'asc').'"></span>'
            : '<span class="sort-icon none"></span>';

        return view('admin.permits.index', compact('permits', 'sortUrl', 'sortIcon'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'requestor_name'    => 'required|string|max:255',
            'applicant_contact' => 'nullable|string|max:50',
            'first_name'        => 'required|string|max:255',
            'middle_name'       => 'nullable|string|max:255',
            'last_name'         => 'required|string|max:255',
            'name_extension'    => 'nullable|string|max:20',
            'date_of_death'     => 'required|date',
            'burial_fee_type'   => 'required|string',
            'nationality'       => 'nullable|string|max:100',
            'age'               => 'nullable|integer|min:0',
            'sex'               => 'nullable|in:Male,Female',
            'kind_of_burial'    => 'nullable|string|max:100',
        ]);

        $deceased = DeceasedPerson::create([
            'first_name'     => $request->first_name,
            'middle_name'    => $request->middle_name,
            'last_name'      => $request->last_name,
            'name_extension' => $request->name_extension,
            'date_of_death'  => $request->date_of_death,
            'nationality'    => $request->nationality ?? 'Filipino',
            'age'            => $request->age,
            'sex'            => $request->sex,
            'kind_of_burial' => $request->kind_of_burial,
        ]);

        // ── Duplicate-safe permit number ──
        $year       = now()->year;
        $lastPermit = BurialPermit::whereYear('created_at', $year)
            ->orderByRaw('CAST(SUBSTRING_INDEX(permit_number, "-", -1) AS UNSIGNED) DESC')
            ->first();
        $nextNumber = $lastPermit
            ? (int) explode('-', $lastPermit->permit_number)[2] + 1
            : 1;
        do {
            $permitNumber = 'BP-'.$year.'-'.str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
            $nextNumber++;
        } while (BurialPermit::where('permit_number', $permitNumber)->exists());

        $permit = BurialPermit::create([
    'permit_number'          => $permitNumber,
    'deceased_id'            => $deceased->id,
    'permit_type'            => $request->burial_fee_type,
    'kind_of_burial'         => $request->kind_of_burial,
    'applicant_name'         => $request->requestor_name,
    'applicant_relationship' => $request->applicant_relationship ?? '',
    'applicant_contact'      => $request->applicant_contact ?? '',
    'status'                 => 'released',
    'issued_date'            => now(),
    'expiry_date'            => now()->addYear(5),
    'processed_by'           => Auth::id(),
]);

        ActivityLog::record(
            action: 'created',
            modelType: 'BurialPermit',
            modelId: $permit->id,
            modelLabel: $permitNumber,
            newValues: $permit->toArray(),
            description: "Permit {$permitNumber} created for {$request->first_name} {$request->last_name}"
        );

        return redirect()->route('permits.show', $permit)
            ->with('success', 'Burial permit created successfully.');
    }

    public function show(BurialPermit $permit)
    {
        $permit->load('deceased', 'processedBy', 'smsNotifications');

        return view('admin.permits.show', compact('permit'));
    }

    public function approve(BurialPermit $permit)
    {
        if ($permit->status !== 'pending') {
            return redirect()->route('permits.show', $permit)
                ->withErrors(['status' => 'Only pending permits can be approved.']);
        }

        $old = $permit->only(['status']);
        $permit->update([
            'status'       => 'approved',
            'processed_by' => Auth::id(),
        ]);

        ActivityLog::record(
            action: 'approved',
            modelType: 'BurialPermit',
            modelId: $permit->id,
            modelLabel: $permit->permit_number,
            oldValues: $old,
            newValues: ['status' => 'approved'],
            description: "Permit {$permit->permit_number} approved"
        );

        return redirect()->route('permits.show', $permit)
            ->with('success', 'Permit approved successfully.');
    }

    public function release(BurialPermit $permit)
    {
        if (! in_array($permit->status, ['pending', 'approved'])) {
            return redirect()->route('permits.show', $permit)
                ->withErrors(['status' => 'This permit cannot be released in its current state.']);
        }

        $old = $permit->only(['status', 'expiry_date']);
        $permit->update([
            'status'      => 'released',
            'issued_date' => $permit->issued_date ?? now(),
            'expiry_date' => now()->addYear(5),
        ]);

        ActivityLog::record(
            action: 'released',
            modelType: 'BurialPermit',
            modelId: $permit->id,
            modelLabel: $permit->permit_number,
            oldValues: $old,
            newValues: ['status' => 'released', 'expiry_date' => now()->addYear(5)->toDateString()],
            description: "Permit {$permit->permit_number} released"
        );

        return redirect()->route('permits.show', $permit)
            ->with('success', 'Permit released successfully.');
    }

    public function print(BurialPermit $permit)
    {
        $permit->load('deceased', 'processedBy');

        $templatePath = storage_path('app/templates/permit.docx');

        if (! file_exists($templatePath)) {
            abort(404, 'Permit template not found at storage/app/templates/permit.docx');
        }

        $feeData = [
            'cemented'    => ['tomb' => '1,000.00', 'permit' => '20.00', 'maint' => '100.00', 'app' => '20.00', 'total' => '1,200.00'],
            'niche_1st'   => ['tomb' => '8,000.00', 'permit' => '20.00', 'maint' => '100.00', 'app' => '20.00', 'total' => '8,200.00'],
            'niche_2nd'   => ['tomb' => '6,600.00', 'permit' => '20.00', 'maint' => '100.00', 'app' => '20.00', 'total' => '6,800.00'],
            'niche_3rd'   => ['tomb' => '5,700.00', 'permit' => '20.00', 'maint' => '100.00', 'app' => '20.00', 'total' => '5,900.00'],
            'niche_4th'   => ['tomb' => '5,300.00', 'permit' => '20.00', 'maint' => '100.00', 'app' => '20.00', 'total' => '5,500.00'],
            'bone_niches' => ['tomb' => '5,000.00', 'permit' => '20.00', 'maint' => '100.00', 'app' => '20.00', 'total' => '5,200.00'],
        ];

        $fees      = $feeData[$permit->permit_type] ?? $feeData['cemented'];
        $isNew     = in_array($permit->status, ['pending', 'approved', 'released']);
        $isRenewal = ($permit->renewal_count ?? 0) > 0;

        $permitType = $permit->permit_type ?? '';
        $isCemented = str_contains($permitType, 'cemented') ? '✓' : ' ';
        $isNiche    = str_contains($permitType, 'niche')    ? '✓' : ' ';
        $isBone     = str_contains($permitType, 'bone')     ? '✓' : ' ';

        $deceased    = $permit->deceased;
        $deceasedName = $deceased ? trim($deceased->first_name.' '.$deceased->last_name) : '—';

        $parts = explode('-', $permit->permit_number ?? '');
        $year  = $parts[1] ?? now()->year;
        $regNo = $parts[2] ?? $permit->permit_number;

        $replacements = [
            '${renewal_check}'   => $isRenewal ? '✓' : ' ',
            '${new_check}'       => $isNew     ? '✓' : ' ',
            '${date}'            => now()->format('F d, Y'),
            '${year}'            => $year,
            '${reg_no}'          => $regNo,
            '${applicant_name}'  => $permit->applicant_name ?? '—',
            '${relationship}'    => $permit->applicant_relationship ?? '—',
            '${applicant_address}' => $permit->applicant_address ?? '—',
            '${contact}'         => $permit->applicant_contact ?? '—',
            '${check_cemented}'  => $isCemented,
            '${check_niche}'     => $isNiche,
            '${check_bone}'      => $isBone,
            '${deceased_name}'   => $deceasedName,
            '${place_of_death}'  => $deceased->place_of_death ?? 'Carmen, Davao del Norte',
            '${date_of_death}'   => $deceased && $deceased->date_of_death
                ? Carbon::parse($deceased->date_of_death)->format('F d, Y')
                : '—',
            '${or_number}'  => '—',
            '${paid_on}'    => $permit->issued_date
                ? Carbon::parse($permit->issued_date)->format('F d, Y')
                : '—',
            '${amount_paid}' => 'P '.$fees['total'],
            '${fee_tomb}'    => $fees['tomb'],
            '${fee_permit}'  => $fees['permit'],
            '${fee_maint}'   => $fees['maint'],
            '${fee_app}'     => $fees['app'],
            '${fee_total}'   => $fees['total'],
            '${expiry_date}' => $permit->expiry_date
                ? Carbon::parse($permit->expiry_date)->format('F d, Y')
                : now()->addYear()->format('F d, Y'),
        ];

        $tmpDocx = sys_get_temp_dir().'/permit_'.$permit->id.'_'.time().'.docx';
        copy($templatePath, $tmpDocx);

        $zip = new \ZipArchive;
        if ($zip->open($tmpDocx) !== true) {
            abort(500, 'Could not open permit template.');
        }

        $docXml = $zip->getFromName('word/document.xml');
        if ($docXml === false) {
            $zip->close();
            abort(500, 'Template is missing word/document.xml');
        }

        foreach ($replacements as $placeholder => $value) {
            $docXml = str_replace(
                htmlspecialchars($placeholder, ENT_XML1),
                htmlspecialchars((string) $value, ENT_XML1),
                $docXml
            );
            $docXml = str_replace($placeholder, htmlspecialchars((string) $value, ENT_XML1), $docXml);
        }

        $zip->addFromString('word/document.xml', $docXml);
        $zip->close();

        return response()->download($tmpDocx, 'BurialPermit_'.$permit->permit_number.'.docx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ])->deleteFileAfterSend(true);
    }

    public function destroy(BurialPermit $permit)
    {
        $label  = $permit->permit_number;
        $source = request('source');
        $fromDQ = $source === 'data_quality';

        $description = $fromDQ
            ? "Permit {$label} deleted via Data Quality Scanner by ".auth()->user()->name
            : "Permit {$label} deleted";

        ActivityLog::record(
            action: 'deleted',
            modelType: 'BurialPermit',
            modelId: $permit->id,
            modelLabel: $label,
            oldValues: $permit->toArray(),
            description: $description
        );

        $deceased = $permit->deceased;
        $permit->delete();
        if ($deceased) {
            $deceased->delete();
        }

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => "Permit {$label} deleted."]);
        }

        return redirect()->route('permits.index')
            ->with('success', 'Permit deleted successfully.');
    }

    public function renew(BurialPermit $permit)
{
    $expiry = $permit->expiry_date ? \Carbon\Carbon::parse($permit->expiry_date) : null;

    $isExpired  = $expiry && $expiry->isPast();
    $isExpiring = $expiry && $expiry->isFuture() && now()->diffInDays($expiry) <= 30;

    if (! $isExpired && ! $isExpiring) {
        return back()->with('error', 'This permit is not eligible for renewal. Only expired or expiring permits can be renewed.');
    }

    $old = $permit->only(['status', 'expiry_date', 'remarks']);

    $newExpiry = now()->addYears(5);

    $permit->update([
        'status'        => 'released',
        'expiry_date'   => $newExpiry,
        'processed_by'  => Auth::id(),
        'renewal_count' => ($permit->renewal_count ?? 0) + 1,
        'remarks'       => 'Last renewed on '.now()->format('F d, Y').' by '.auth()->user()->name,
    ]);

    ActivityLog::record(
            action: 'renewed',
            modelType: 'BurialPermit',
            modelId: $permit->id,
            modelLabel: $permit->permit_number,
            oldValues: $old,
            newValues: [
                'status'      => 'released',
                'expiry_date' => $newExpiry->toDateString(),
                'remarks'     => $permit->fresh()->remarks,
            ],
            description: "Permit {$permit->permit_number} renewed until " . $newExpiry->format('F d, Y')
        );

    return redirect()->route('permits.show', $permit)
        ->with('success', "Permit {$permit->permit_number} renewed successfully. New expiry: ".$newExpiry->format('F d, Y').'.');
}

    private function sortUrl(string $col): string
    {
        $current   = request()->get('sort', 'status');
        $direction = request()->get('direction', 'asc');
        $newDir    = ($current === $col && $direction === 'asc') ? 'desc' : 'asc';

        return request()->fullUrlWithQuery(['sort' => $col, 'direction' => $newDir, 'page' => 1]);
    }

    private function sortIcon(string $col): string
    {
        $current   = request()->get('sort', 'status');
        $direction = request()->get('direction', 'asc');
        if ($current !== $col) {
            return '<span class="sort-icon none"></span>';
        }
        $cls = $direction === 'asc' ? 'asc' : 'desc';

        return "<span class=\"sort-icon {$cls}\"></span>";
    }

    public function create() {}

    public function edit(BurialPermit $permit) {}

    public function update(Request $request, BurialPermit $permit)
    {
        $request->validate([
            'first_name'        => 'required|string|max:100',
            'last_name'         => 'required|string|max:100',
            'age'               => 'nullable|integer|min:0|max:150',
            'sex'               => 'nullable|in:Male,Female',
            'nationality'       => 'nullable|string|max:100',
            'date_of_death'     => 'nullable|date',
            'place_of_death'    => 'nullable|string|max:255',
            'address'           => 'nullable|string|max:255',
            'kind_of_burial'    => 'nullable|string|max:100',
            'permit_type'       => 'required|string',
            'status'            => 'nullable|in:active,expiring,expired',
            'expiry_date'       => 'nullable|date',
            'remarks'           => 'nullable|string|max:500',
            'applicant_name'    => 'nullable|string|max:150',
            'applicant_contact' => 'nullable|string|max:50',
            'applicant_address' => 'nullable|string|max:255',
        ]);

        if ($permit->deceased) {
            $permit->deceased->update([
                'first_name'     => $request->first_name,
                'last_name'      => $request->last_name,
                'age'            => $request->age,
                'sex'            => $request->sex,
                'nationality'    => $request->nationality,
                'date_of_death'  => $request->date_of_death,
                'place_of_death' => $request->place_of_death,
                'address'        => $request->address,
                'kind_of_burial' => $request->kind_of_burial,
            ]);
        }

        $old = $permit->only(['permit_type', 'expiry_date', 'applicant_name', 'applicant_contact', 'applicant_address']);

        $permit->update([
            'permit_type'       => $request->permit_type,
            'expiry_date'       => $request->expiry_date,
            'applicant_name'    => $request->applicant_name,
            'applicant_contact' => $request->applicant_contact,
            'applicant_address' => $request->applicant_address,
        ]);

        ActivityLog::record(
            action: 'updated',
            modelType: 'BurialPermit',
            modelId: $permit->id,
            modelLabel: $permit->permit_number,
            oldValues: $old,
            newValues: $permit->fresh()->only(['permit_type', 'expiry_date', 'applicant_name', 'applicant_contact', 'applicant_address']),
            description: "Permit {$permit->permit_number} updated by " . auth()->user()->name
        );

        return redirect()->route('permits.show', $permit)
            ->with('success', 'Permit updated successfully.');
    }
}
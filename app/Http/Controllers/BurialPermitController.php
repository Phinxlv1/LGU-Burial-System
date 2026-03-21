<?php

namespace App\Http\Controllers;

use App\Models\BurialPermit;
use App\Models\DeceasedPerson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BurialPermitController extends Controller
{
    public function index(Request $request)
    {
        $sort      = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc') === 'asc' ? 'asc' : 'desc';

        $query = BurialPermit::with('deceased');

        if ($sort === 'last_name') {
            $query->orderByRaw("(SELECT last_name FROM deceased_persons WHERE deceased_persons.id = burial_permits.deceased_id) {$direction}");
        } elseif ($sort === 'date_of_death') {
            $query->orderByRaw("(SELECT date_of_death FROM deceased_persons WHERE deceased_persons.id = burial_permits.deceased_id) {$direction}");
        } elseif (in_array($sort, ['permit_number', 'permit_type', 'created_at', 'status'])) {
            $query->orderBy($sort, $direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $permits = $query->paginate(10)->withQueryString();

        $sortUrl = fn(string $col) => request()->fullUrlWithQuery([
            'sort'      => $col,
            'direction' => (request()->get('sort') === $col && request()->get('direction') === 'asc') ? 'desc' : 'asc',
            'page'      => 1,
        ]);

        $sortIcon = fn(string $col) => request()->get('sort') === $col
            ? '<span class="sort-icon ' . request()->get('direction', 'desc') . '"></span>'
            : '<span class="sort-icon none"></span>';

        return view('permits.index', compact('permits', 'sortUrl', 'sortIcon'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'requestor_name'   => 'required|string|max:255',
            'first_name'       => 'required|string|max:255',
            'middle_name'      => 'nullable|string|max:255',
            'last_name'        => 'required|string|max:255',
            'extension'        => 'nullable|string|max:10',
            'date_of_death'    => 'required|date',
            'burial_fee_type'  => 'required|string',
            'nationality'      => 'nullable|string|max:100',
            'age'              => 'nullable|integer|min:0',
            'sex'              => 'nullable|in:Male,Female',
            'kind_of_burial'   => 'nullable|string|max:100',
        ]);

        // Build last name with extension if provided (e.g. "Santos Jr.")
        $lastName = trim($request->last_name);
        if ($request->filled('extension')) {
            $lastName = $lastName . ' ' . trim($request->extension);
        }

        $deceased = DeceasedPerson::create([
            'first_name'     => $request->first_name,
            'middle_name'    => $request->middle_name ?: null,
            'last_name'      => $lastName,
            'date_of_death'  => $request->date_of_death,
            'nationality'    => $request->nationality ?? 'Filipino',
            'age'            => $request->age,
            'sex'            => $request->sex,
            'kind_of_burial' => $request->kind_of_burial,
        ]);

        // ── Generate unique permit number ──
        // Find the highest existing permit number for this year and increment from there
        $lastPermit = BurialPermit::whereYear('created_at', now()->year)
            ->orderByRaw('CAST(SUBSTRING_INDEX(permit_number, "-", -1) AS UNSIGNED) DESC')
            ->first();

        if ($lastPermit) {
            $lastNumber = (int) explode('-', $lastPermit->permit_number)[2];
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        // Keep incrementing until we find a number that doesn't exist
        do {
            $permitNumber = 'BP-' . now()->year . '-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
            $nextNumber++;
        } while (BurialPermit::where('permit_number', $permitNumber)->exists());

        BurialPermit::create([
            'permit_number'          => $permitNumber,
            'deceased_id'            => $deceased->id,
            'permit_type'            => $request->burial_fee_type,
            'kind_of_burial'         => $request->kind_of_burial,
            'applicant_name'         => $request->requestor_name,
            'applicant_relationship' => $request->applicant_relationship ?? '',
            'applicant_contact'      => $request->applicant_contact ?? '',
            'status'                 => 'pending',
            'processed_by'           => Auth::id(),
        ]);

        return redirect()->route('permits.index')
            ->with('success', 'Burial permit created successfully.');
    }

    public function show(BurialPermit $permit)
    {
        $permit->load('deceased', 'processedBy', 'smsNotifications');
        return view('permits.show', compact('permit'));
    }

    public function approve(BurialPermit $permit)
    {
        if ($permit->status !== 'pending') {
            return redirect()->route('permits.show', $permit)
                ->withErrors(['status' => 'Only pending permits can be approved.']);
        }

        $permit->update([
            'status'       => 'approved',
            'issued_date'  => now(),
            'processed_by' => Auth::id(),
        ]);

        return redirect()->route('permits.show', $permit)
            ->with('success', 'Permit approved successfully.');
    }

    public function release(BurialPermit $permit)
    {
        if (!in_array($permit->status, ['pending', 'approved'])) {
            return redirect()->route('permits.show', $permit)
                ->withErrors(['status' => 'This permit cannot be released in its current state.']);
        }

        $permit->update([
            'status'      => 'released',
            'issued_date' => $permit->issued_date ?? now(),
            'expiry_date' => now()->addYear(),
        ]);

        return redirect()->route('permits.show', $permit)
            ->with('success', 'Permit released successfully.');
    }

    public function print(BurialPermit $permit)
    {
        $permit->load('deceased', 'processedBy');

        $templatePath = storage_path('app/templates/permit.docx');

        if (!file_exists($templatePath)) {
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
        $isRenewal = false;

        $permitType = $permit->permit_type ?? '';
        $isCemented = str_contains($permitType, 'cemented') ? '✓' : ' ';
        $isNiche    = str_contains($permitType, 'niche')    ? '✓' : ' ';
        $isBone     = str_contains($permitType, 'bone')     ? '✓' : ' ';

        $deceased     = $permit->deceased;
        $deceasedName = $deceased ? trim($deceased->first_name . ' ' . $deceased->last_name) : '—';

        $parts  = explode('-', $permit->permit_number ?? '');
        $year   = $parts[1] ?? now()->year;
        $regNo  = $parts[2] ?? $permit->permit_number;

        $replacements = [
            '${renewal_check}'     => $isRenewal ? '✓' : ' ',
            '${new_check}'         => $isNew     ? '✓' : ' ',
            '${date}'              => now()->format('F d, Y'),
            '${year}'              => $year,
            '${reg_no}'            => $regNo,
            '${applicant_name}'    => $permit->applicant_name ?? '—',
            '${relationship}'      => $permit->applicant_relationship ?? '—',
            '${applicant_address}' => $permit->applicant_address ?? '—',
            '${contact}'           => $permit->applicant_contact ?? '—',
            '${check_cemented}'    => $isCemented,
            '${check_niche}'       => $isNiche,
            '${check_bone}'        => $isBone,
            '${deceased_name}'     => $deceasedName,
            '${place_of_death}'    => $deceased->place_of_death ?? 'Carmen, Davao del Norte',
            '${date_of_death}'     => $deceased && $deceased->date_of_death
                                          ? \Carbon\Carbon::parse($deceased->date_of_death)->format('F d, Y')
                                          : '—',
            '${or_number}'         => '—',
            '${paid_on}'           => $permit->issued_date
                                          ? \Carbon\Carbon::parse($permit->issued_date)->format('F d, Y')
                                          : '—',
            '${amount_paid}'       => 'P ' . $fees['total'],
            '${fee_tomb}'          => $fees['tomb'],
            '${fee_permit}'        => $fees['permit'],
            '${fee_maint}'         => $fees['maint'],
            '${fee_app}'           => $fees['app'],
            '${fee_total}'         => $fees['total'],
            '${expiry_date}'       => $permit->expiry_date
                                          ? \Carbon\Carbon::parse($permit->expiry_date)->format('F d, Y')
                                          : now()->addYear()->format('F d, Y'),
        ];

        $tmpDocx = sys_get_temp_dir() . '/permit_' . $permit->id . '_' . time() . '.docx';
        copy($templatePath, $tmpDocx);

        $zip = new \ZipArchive();
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

        return response()->download($tmpDocx, 'BurialPermit_' . $permit->permit_number . '.docx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ])->deleteFileAfterSend(true);
    }

    public function destroy(BurialPermit $permit)
    {
        $deceased = $permit->deceased;
        $permit->delete();
        if ($deceased) {
            $deceased->delete();
        }
        return redirect()->route('permits.index')
            ->with('success', 'Permit deleted successfully.');
    }

    public function renew(BurialPermit $permit)
    {
        $isExpired  = $permit->status === 'expired';
        $isExpiring = $permit->status === 'released'
                      && $permit->expiry_date
                      && $permit->expiry_date->diffInDays(now()) <= 30
                      && $permit->expiry_date->isFuture();

        abort_if(!$isExpired && !$isExpiring, 403, 'This permit is not eligible for renewal.');

        $permit->update([
            'status'       => 'released',
            'expiry_date'  => now()->addYears(5),
            'processed_by' => Auth::id(),
            'remarks'      => ($permit->remarks ? $permit->remarks . ' | ' : '')
                              . 'Renewed on ' . now()->format('F d, Y') . ' by ' . auth()->user()->name,
        ]);

        return redirect()->route('dashboard')
            ->with('success', "Permit {$permit->permit_number} has been renewed successfully. New expiry: " . $permit->fresh()->expiry_date->format('F d, Y') . '.');
    }

    private function sortUrl(string $col): string
    {
        $current   = request()->get('sort', 'created_at');
        $direction = request()->get('direction', 'desc');
        $newDir    = ($current === $col && $direction === 'asc') ? 'desc' : 'asc';
        return request()->fullUrlWithQuery(['sort' => $col, 'direction' => $newDir, 'page' => 1]);
    }

    private function sortIcon(string $col): string
    {
        $current   = request()->get('sort', 'created_at');
        $direction = request()->get('direction', 'desc');
        if ($current !== $col) return '<span class="sort-icon none"></span>';
        $cls = $direction === 'asc' ? 'asc' : 'desc';
        return "<span class=\"sort-icon {$cls}\"></span>";
    }

    public function create() {}
    public function edit(BurialPermit $permit) {}
    public function update(Request $request, BurialPermit $permit) {}
}
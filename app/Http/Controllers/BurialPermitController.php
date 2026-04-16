<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\BurialPermit;
use App\Models\DeceasedPerson;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Services\BurialPermitService;

class BurialPermitController extends Controller
{
    protected $permitService;

    public function __construct(BurialPermitService $permitService)
    {
        $this->permitService = $permitService;
    }

    private function loadSettings(): array
    {
        return $this->permitService->getSettings();
    }

    public function index(Request $request)
    {
        $sort = $request->get('sort', 'status');
        $validDirs = ['asc', 'desc', 'mid', 't1', 't2', 't3', 't4', 't5', 't6'];
        $direction = in_array($request->get('direction', 'asc'), $validDirs)
            ? $request->get('direction', 'asc')
            : 'asc';

        $query = BurialPermit::with('deceased');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($qq) use ($q) {
                $qq->where('permit_number', 'like', "%{$q}%")
                   ->orWhereHas('deceased', function($dq) use ($q) {
                       $dq->where('first_name', 'like', "%{$q}%")
                          ->orWhere('last_name', 'like', "%{$q}%")
                          ->orWhere('middle_name', 'like', "%{$q}%");
                   });
            });
        }

        if ($sort === 'last_name') {
            $query->orderByRaw(
                "(SELECT last_name FROM deceased_persons WHERE deceased_persons.id = burial_permits.deceased_id) {$direction}"
            );

        } elseif ($sort === 'date_of_death') {
            $query->orderByRaw(
                "(SELECT date_of_death FROM deceased_persons WHERE deceased_persons.id = burial_permits.deceased_id) {$direction}"
            );

        } elseif ($sort === 'status') {
            if ($direction === 'mid') {
                $query->orderByRaw("
            CASE
                WHEN status = 'expiring' THEN 1
                WHEN status = 'expired'  THEN 2
                WHEN status = 'active'   THEN 3
                ELSE 4
            END ASC, permit_number ASC
        ");
            } else {
                $query->orderByRaw("
            CASE
                WHEN status = 'expired'  THEN 1
                WHEN status = 'expiring' THEN 2
                WHEN status = 'active'   THEN 3
                ELSE 4
            END {$direction}, permit_number ASC
        ");
            }

        } elseif ($sort === 'permit_type') {
            $orderMap = [
                't1' => "WHEN permit_type = 'cemented' THEN 1 ELSE 2",
                't2' => "WHEN permit_type = 'niche_1st' THEN 1 ELSE 2",
                't3' => "WHEN permit_type = 'niche_2nd' THEN 1 ELSE 2",
                't4' => "WHEN permit_type = 'niche_3rd' THEN 1 ELSE 2",
                't5' => "WHEN permit_type = 'niche_4th' THEN 1 ELSE 2",
                't6' => "WHEN permit_type = 'bone_niches' THEN 1 ELSE 2",
            ];
            $case = $orderMap[$direction] ?? $orderMap['t1'];

            $query->orderByRaw("
                CASE {$case} END ASC,
                CASE
                    WHEN permit_type = 'cemented' THEN 1
                    WHEN permit_type = 'niche_1st' THEN 2
                    WHEN permit_type = 'niche_2nd' THEN 3
                    WHEN permit_type = 'niche_3rd' THEN 4
                    WHEN permit_type = 'niche_4th' THEN 5
                    WHEN permit_type = 'bone_niches' THEN 6
                    ELSE 7
                END ASC, permit_number ASC
            ");

        } elseif (in_array($sort, ['permit_number', 'created_at', 'renewal_count'])) {
            $query->orderBy($sort, $direction);

        } else {
            $query->orderBy('created_at', 'desc');
        }

        $permits = $query->paginate(10)->withQueryString();

        $sortUrl = function (string $col) {
            if ($col === 'status') {
                $cur = request()->get('sort', 'status');
                $dir = request()->get('direction', 'asc');
                if ($cur !== 'status') {
                    $nextDir = 'asc';
                } elseif ($dir === 'asc') {
                    $nextDir = 'desc';
                } elseif ($dir === 'desc') {
                    $nextDir = 'mid';
                } else {
                    $nextDir = 'asc';
                }
                return request()->fullUrlWithQuery(['sort' => 'status', 'direction' => $nextDir, 'page' => 1]);
            }
            if ($col === 'permit_type') {
                $cur = request()->get('sort', 'status');
                $dir = request()->get('direction', 't1');
                if ($cur !== 'permit_type') {
                    $nextDir = 't1';
                } else {
                    switch ($dir) {
                        case 't1':
                            $nextDir = 't2';
                            break;
                        case 't2':
                            $nextDir = 't3';
                            break;
                        case 't3':
                            $nextDir = 't4';
                            break;
                        case 't4':
                            $nextDir = 't5';
                            break;
                        case 't5':
                            $nextDir = 't6';
                            break;
                        case 't6':
                            $nextDir = 't1';
                            break;
                        default:
                            $nextDir = 't1';
                            break;
                    }
                }
                return request()->fullUrlWithQuery(['sort' => 'permit_type', 'direction' => $nextDir, 'page' => 1]);
            }
            return request()->fullUrlWithQuery([
                'sort' => $col,
                'direction' => (request()->get('sort', 'status') === $col && request()->get('direction', 'asc') === 'asc') ? 'desc' : 'asc',
                'page' => 1,
            ]);
        };

        $sortIcon = fn(string $col) => request()->get('sort', 'status') === $col
            ? '<span class="sort-icon ' . request()->get('direction', 'desc') . '"></span>'
            : '<span class="sort-icon none"></span>';

        if ($request->ajax()) {
            return response()
                ->view('admin.permits.partials.table', compact('permits', 'sortUrl', 'sortIcon'))
                ->header('X-Total-Records', $permits->total());
        }

        return view('admin.permits.index', compact('permits', 'sortUrl', 'sortIcon'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'requestor_name' => 'required|string|max:255',
            'applicant_contact' => 'nullable|string|max:50',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'name_extension' => 'nullable|string|max:20',
            'date_of_death' => 'required|date',
            'burial_fee_type' => 'required|string',
            'nationality' => 'nullable|string|max:100',
            'age' => 'nullable|integer|min:0',
            'sex' => 'nullable|in:Male,Female',
            'kind_of_burial' => 'nullable|string|max:100',
        ]);

        // ── Anti-Duplication Check ──
        $duplicate = $this->permitService->checkDuplicate($request->all());

        if ($duplicate) {
            $p = $duplicate->permits()->latest()->first();
            $redirectUrl = $p ? route('permits.show', $p->id) : null;
            $pNum = $p ? $p->permit_number : 'the existing record';

            return back()->withInput()
                ->with('error', "Cannot create permit: A person with the name \"{$duplicate->full_name}\" already has an existing record.")
                ->with('open_modal', true)
                ->with('redirect_url', $redirectUrl)
                ->with('redirect_name', $pNum);
        }

        // ── Similarity Protection ──
        $similar = $this->permitService->checkSimilar($request->first_name, $request->last_name);

        if ($similar) {
            $p = $similar->permits()->latest()->first();
            $redirectUrl = $p ? route('permits.show', $p->id) : null;
            $pNum = $p ? $p->permit_number : 'the existing record';

            return back()->withInput()
                ->with('error', "Cannot create permit: A person with the name \"{$similar->full_name}\" already exists. Please provide distinguishing details.")
                ->with('open_modal', true)
                ->with('redirect_url', $redirectUrl)
                ->with('redirect_name', $pNum);
        }

        $deceased = DeceasedPerson::create([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'name_extension' => $request->name_extension,
            'date_of_death' => $request->date_of_death,
            'nationality' => $request->nationality ?? 'Filipino',
            'age' => $request->age,
            'sex' => $request->sex,
            'kind_of_burial' => $request->kind_of_burial,
            'place_of_death' => $request->place_of_death,
            'address' => $request->address,
        ]);

        $permitNumber = $this->permitService->generatePermitNumber();

        $permit = BurialPermit::create([
            'permit_number' => $permitNumber,
            'deceased_id' => $deceased->id,
            'permit_type' => $request->burial_fee_type,
            'kind_of_burial' => $request->kind_of_burial,
            'applicant_name' => $request->requestor_name,
            'applicant_relationship' => $request->applicant_relationship ?? '',
            'applicant_contact' => $request->applicant_contact ?? '',
            'status' => 'active',
            'issued_date' => now(),
            'expiry_date' => now()->addYears((int) ($this->loadSettings()['permit_expiry_years'] ?? 5)),
            'processed_by' => Auth::id(),
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
            ->with('success', 'Burial permit created successfully.')
            ->with('open_edit', true);
    }

    public function show(BurialPermit $permit)
    {
        $permit->load('deceased', 'processedBy', 'smsNotifications', 'documents');

        $renewalHistory = \App\Models\ActivityLog::where('model_type', 'BurialPermit')
            ->where('model_id', $permit->id)
            ->where('action', 'renewed')
            ->latest()
            ->get();

        return view('admin.permits.show', compact('permit', 'renewalHistory'));
    }

    public function print(BurialPermit $permit)
    {
        $permit->load('deceased', 'processedBy');

        $templatePath = storage_path('app/templates/permit.docx');

        if (!file_exists($templatePath)) {
            abort(404, 'Permit template not found at storage/app/templates/permit.docx');
        }

        $settingsPath = storage_path('app/settings.json');
        $settings = file_exists($settingsPath) ? json_decode(file_get_contents($settingsPath), true) : [];

        $defaultFees = [
            'cemented' => ['tomb' => 1000, 'permit' => 20, 'maint' => 100, 'app' => 20],
            'niche_1st' => ['tomb' => 8000, 'permit' => 20, 'maint' => 100, 'app' => 20],
            'niche_2nd' => ['tomb' => 6600, 'permit' => 20, 'maint' => 100, 'app' => 20],
            'niche_3rd' => ['tomb' => 5700, 'permit' => 20, 'maint' => 100, 'app' => 20],
            'niche_4th' => ['tomb' => 5300, 'permit' => 20, 'maint' => 100, 'app' => 20],
            'bone_niches' => ['tomb' => 5000, 'permit' => 20, 'maint' => 100, 'app' => 20],
        ];

        $permitType = $permit->permit_type ?? 'cemented';
        $rawFees = $settings['fees'][$permitType] ?? $defaultFees[$permitType] ?? $defaultFees['cemented'];

        $totalFee = ($rawFees['tomb'] ?? 0) + ($rawFees['permit'] ?? 0) + ($rawFees['maint'] ?? 0) + ($rawFees['app'] ?? 0);

        $fees = [
            'tomb' => number_format((float) ($rawFees['tomb'] ?? 0), 2),
            'permit' => number_format((float) ($rawFees['permit'] ?? 0), 2),
            'maint' => number_format((float) ($rawFees['maint'] ?? 0), 2),
            'app' => number_format((float) ($rawFees['app'] ?? 0), 2),
            'total' => number_format((float) $totalFee, 2),
        ];
        $isNewActual = !($permit->renewal_count > 0);
        $isRenewalActual = ($permit->renewal_count > 0);

        $permitTypeStr = $permit->permit_type ?? '';
        $isCemented = str_contains($permitTypeStr, 'cemented') ? '✓' : ' ';
        $isNiche = str_contains($permitTypeStr, 'niche') ? '✓' : ' ';
        $isBone = str_contains($permitTypeStr, 'bone') ? '✓' : ' ';

        $deceased = $permit->deceased;
        $deceasedName = $deceased ? trim($deceased->first_name . ' ' . $deceased->last_name) : '—';

        $parts = explode('-', $permit->permit_number ?? '');
        $year = $parts[1] ?? now()->year;
        $regNo = $parts[2] ?? $permit->permit_number;

        // Clean up symbols like "=" from strings
        $clean = fn($val) => (trim($val) === '=' || empty($val)) ? '—' : $val;

        $replacements = [
            '${renewal_check}' => $isRenewalActual ? '✓' : ' ',
            '${new_check}' => $isNewActual ? '✓' : ' ',
            '${date}' => now()->format('F d, Y'),
            '${year}' => $year,
            '${reg_no}' => $regNo,
            '${applicant_name}' => $clean($permit->applicant_name),
            '${relationship}' => $clean($permit->applicant_relationship),
            '${applicant_address}' => $clean($permit->applicant_address),
            '${contact}' => $clean($permit->applicant_contact),
            '${check_cemented}' => $isCemented,
            '${check_niche}' => $isNiche,
            '${check_bone}' => $isBone,
            '${deceased_name}' => $deceasedName,
            '${place_of_death}' => $deceased->place_of_death ?? 'Carmen, Davao del Norte',
            '${date_of_death}' => $deceased && $deceased->date_of_death
                ? Carbon::parse($deceased->date_of_death)->format('F d, Y')
                : '—',
            '${or_number}' => $clean($permit->or_number),
            '${paid_on}' => $permit->issued_date
                ? Carbon::parse($permit->issued_date)->format('F d, Y')
                : '—',
            '${amount_paid}' => 'P ' . $fees['total'],
            '${fee_tomb}' => $fees['tomb'],
            '${fee_permit}' => $fees['permit'],
            '${fee_maint}' => $fees['maint'],
            '${fee_app}' => $fees['app'],
            '${fee_total}' => $fees['total'],
            '${expiry_date}' => $permit->expiry_date
                ? Carbon::parse($permit->expiry_date)->format('F d, Y')
                : now()->addYear()->format('F d, Y'),
        ];

        $tmpDocx = sys_get_temp_dir() . '/permit_' . $permit->id . '_' . time() . '.docx';
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

        return response()->download($tmpDocx, 'BurialPermit_' . $permit->permit_number . '.docx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ])->deleteFileAfterSend(true);
    }

    public function destroy(BurialPermit $permit)
    {
        $label = $permit->permit_number;
        $source = request('source');
        $fromDQ = $source === 'data_quality';

        $description = $fromDQ
            ? "Permit {$label} deleted via Data Quality Scanner by " . auth()->user()->name
            : "Permit {$label} deleted by " . auth()->user()->name;

        ActivityLog::record(
            action: 'deleted',
            modelType: 'BurialPermit',
            modelId: $permit->id,
            modelLabel: $label,
            oldValues: $permit->toArray(),
            description: $description
        );

        $deceased = $permit->deceased;

        // ── Clean up storage (Delete all uploaded files for this permit) ──
        $storageDir = 'permits/' . $permit->id;
        if (\Illuminate\Support\Facades\Storage::disk('local')->exists($storageDir)) {
            \Illuminate\Support\Facades\Storage::disk('local')->deleteDirectory($storageDir);
        }

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
        $years = (int) ($this->loadSettings()['permit_expiry_years'] ?? 5);

        try {
            $this->permitService->renewPermit($permit, $years);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('permits.show', $permit)
            ->with('success', "Permit {$permit->permit_number} renewed successfully.");
    }

    public function create()
    {
    }

    public function edit(BurialPermit $permit)
    {
    }

    public function update(Request $request, BurialPermit $permit)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'age' => 'nullable|integer|min:0|max:150',
            'sex' => 'nullable|in:Male,Female',
            'nationality' => 'nullable|string|max:100',
            'date_of_death' => 'nullable|date',
            'place_of_death' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'kind_of_burial' => 'nullable|string|max:100',
            'permit_type' => 'required|string',
            'status' => 'nullable|in:active,expiring,expired',
            'expiry_date' => 'nullable|date',
            'remarks' => 'nullable|string|max:500',
            'applicant_name' => 'nullable|string|max:150',
            'applicant_contact' => 'nullable|string|max:50',
            'applicant_address' => 'nullable|string|max:255',
            'or_number' => 'nullable|string|max:100',
        ]);

        if ($permit->deceased) {
            $permit->deceased->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'age' => $request->age,
                'sex' => $request->sex,
                'nationality' => $request->nationality,
                'date_of_death' => $request->date_of_death,
                'place_of_death' => $request->place_of_death,
                'address' => $request->address,
                'kind_of_burial' => $request->kind_of_burial,
            ]);
        }

        $old = $permit->only(['permit_type', 'expiry_date', 'applicant_name', 'applicant_contact', 'applicant_address', 'or_number']);

        $permit->update([
            'permit_type' => $request->permit_type,
            'kind_of_burial' => $request->kind_of_burial,
            'expiry_date' => $request->expiry_date,
            'applicant_name' => $request->applicant_name,
            'applicant_contact' => $request->applicant_contact,
            'applicant_address' => $request->applicant_address,
            'or_number' => $request->or_number,
        ]);

        ActivityLog::record(
            action: 'updated',
            modelType: 'BurialPermit',
            modelId: $permit->id,
            modelLabel: $permit->permit_number,
            oldValues: $old,
            newValues: $permit->fresh()->only(['permit_type', 'expiry_date', 'applicant_name', 'applicant_contact', 'applicant_address', 'or_number']),
            description: "Permit {$permit->permit_number} updated by " . auth()->user()->name
        );

        return redirect()->route('permits.show', $permit)
            ->with('success', 'Permit updated successfully.');
    }
}
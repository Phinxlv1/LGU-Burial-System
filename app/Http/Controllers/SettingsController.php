<?php

namespace App\Http\Controllers;

use App\Models\BurialPermit;
use App\Models\DeceasedPerson;
use App\Models\ImportLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    // ── Settings are stored as a JSON file in storage ──
    private string $path;

    public function __construct()
    {
        $this->path = storage_path('app/settings.json');
    }

    private function load(): array
    {
        if (! file_exists($this->path)) {
            return [];
        }

        return json_decode(file_get_contents($this->path), true) ?? [];
    }

    private function save(array $data): void
    {
        file_put_contents($this->path, json_encode($data, JSON_PRETTY_PRINT));
    }

    // ──────────────────────────────────────────────────
    // GET /settings
    // ──────────────────────────────────────────────────
    public function index()
    {
        $settings = $this->load();
        $users = User::orderBy('created_at')->get();

        return view('settings.index', compact('settings', 'users'));
    }

    // ──────────────────────────────────────────────────
    // PUT /settings/{section}
    // ──────────────────────────────────────────────────
    public function update(Request $request, string $section)
    {
        $settings = $this->load();

        match ($section) {
            'general' => $this->updateGeneral($request, $settings),
            'fees' => $this->updateFees($request, $settings),
            'notifications' => $this->updateNotifications($request, $settings),
            default => abort(404),
        };

        $this->save($settings);

        return redirect()->route('settings.index')
            ->with('success', ucfirst($section).' settings saved successfully.');
    }

    private function updateGeneral(Request $request, array &$settings): void
    {
        $request->validate([
            'municipality_name' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'registrar_name' => 'nullable|string|max:100',
            'mayor_name' => 'nullable|string|max:100',
            'office_address' => 'nullable|string|max:255',
            'permit_prefix' => 'required|string|max:10',
            'permit_expiry_years' => 'required|integer|in:1,2,3,5',
            'expiry_warning_days' => 'required|integer|min:7|max:90',
            'date_format' => 'required|string',
            'per_page' => 'required|integer|in:10,15,20,25,50',
        ]);

        foreach ([
            'municipality_name', 'province', 'registrar_name', 'mayor_name',
            'office_address', 'permit_prefix', 'permit_expiry_years',
            'expiry_warning_days', 'date_format', 'per_page',
        ] as $key) {
            $settings[$key] = $request->input($key);
        }
    }

    private function updateFees(Request $request, array &$settings): void
    {
        $feeKeys = ['cemented', 'niche_1st', 'niche_2nd', 'niche_3rd', 'niche_4th', 'bone_niches'];

        foreach ($feeKeys as $key) {
            $settings['fees'][$key] = [
                'tomb' => (int) $request->input("fees.{$key}.tomb", 0),
                'permit' => (int) $request->input("fees.{$key}.permit", 0),
                'maint' => (int) $request->input("fees.{$key}.maint", 0),
                'app' => (int) $request->input("fees.{$key}.app", 0),
            ];
        }
    }

    private function updateNotifications(Request $request, array &$settings): void
    {
        foreach (['notify_expiring', 'notify_new_permit', 'highlight_expired', 'notify_import', 'notify_skip_reasons'] as $key) {
            $settings[$key] = $request->boolean($key);
        }
    }

    // ──────────────────────────────────────────────────
    // POST /settings/reset/{target}
    // ──────────────────────────────────────────────────
    public function reset(string $target)
    {
        $settings = $this->load();

        switch ($target) {
            case 'fees':
                unset($settings['fees']);
                $msg = 'Permit fees reset to defaults.';
                break;

            case 'import-logs':
                ImportLog::truncate();
                $msg = 'Import history cleared.';
                break;

            case 'all':
                $settings = [];
                $msg = 'All settings reset to defaults.';
                break;

            default:
                abort(404);
        }

        $this->save($settings);

        return redirect()->route('settings.index')->with('success', $msg);
    }

    // ──────────────────────────────────────────────────
    // POST /settings/users   — add user
    // ──────────────────────────────────────────────────
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,super_admin',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Assign Spatie role too
        try {
            $user->assignRole($request->role);
        } catch (\Throwable) {
        }

        return redirect()->route('settings.index', ['#users'])
            ->with('success', "{$request->name} added successfully.");
    }

    // ──────────────────────────────────────────────────
    // DELETE /settings/users/{user}   — remove user
    // ──────────────────────────────────────────────────
    public function destroyUser(User $user)
    {
        abort_if($user->id === auth()->id(), 403, 'You cannot remove your own account.');

        $name = $user->name;
        $user->delete();

        return redirect()->route('settings.index', ['#users'])
            ->with('success', "{$name} removed successfully.");
    }

    public function dataQualityScan()
    {
        $issues = [];

        // ── 1. DUPLICATE PERMITS: same deceased_id appears more than once ──
        $dupPermits = BurialPermit::select('deceased_id', DB::raw('COUNT(*) as cnt'), DB::raw('GROUP_CONCAT(id) as ids'))
            ->groupBy('deceased_id')
            ->having('cnt', '>', 1)
            ->with('deceased')
            ->get();

        if ($dupPermits->isNotEmpty()) {
            $records = [];
            foreach ($dupPermits as $dup) {
                $permitIds = explode(',', $dup->ids);
                $permits = BurialPermit::whereIn('id', $permitIds)->get();
                foreach ($permits as $p) {
                    $deceased = $dup->deceased;
                    $records[] = [
                        'id' => 'dup-permit-'.$p->id,
                        'permit_id' => $p->id,
                        'label' => $p->permit_number,
                        'sub' => ($deceased ? $deceased->last_name.', '.$deceased->first_name : 'Unknown').' — '.ucfirst($p->status),
                        'field_name' => 'deceased_id',
                        'field_value' => (string) $p->deceased_id,
                        'edit_url' => route('permits.show', $p),
                    ];
                }
            }
            $issues[] = [
                'id' => 'dup-same-deceased',
                'type' => 'duplicate',
                'severity' => 'high',
                'title' => 'Multiple permits for the same deceased person',
                'description' => 'Each deceased person should have only one active permit. Review these and delete the duplicate(s) or verify they are distinct cases.',
                'records' => $records,
            ];
        }

        // ── 2. DUPLICATE PERMIT NUMBERS ──
        $dupNums = BurialPermit::select('permit_number', DB::raw('COUNT(*) as cnt'))
            ->groupBy('permit_number')
            ->having('cnt', '>', 1)
            ->pluck('permit_number');

        if ($dupNums->isNotEmpty()) {
            $records = [];
            foreach ($dupNums as $num) {
                $permits = BurialPermit::where('permit_number', $num)->with('deceased')->get();
                foreach ($permits as $p) {
                    $records[] = [
                        'id' => 'dupnum-'.$p->id,
                        'permit_id' => $p->id,
                        'label' => $p->permit_number,
                        'sub' => optional($p->deceased)->last_name.', '.optional($p->deceased)->first_name,
                        'field_name' => 'permit_number',
                        'field_value' => $p->permit_number,
                        'edit_url' => route('permits.show', $p),
                    ];
                }
            }
            $issues[] = [
                'id' => 'dup-permit-numbers',
                'type' => 'duplicate',
                'severity' => 'high',
                'title' => 'Duplicate permit numbers detected',
                'description' => 'Permit numbers must be unique. These permits share the same number — delete the duplicate or correct the numbering.',
                'records' => $records,
            ];
        }

        // ── 3. MISSING: permits with no deceased linked ──
        $noDeceased = BurialPermit::whereNull('deceased_id')->orWhereDoesntHave('deceased')->get();
        if ($noDeceased->isNotEmpty()) {
            $issues[] = [
                'id' => 'missing-deceased',
                'type' => 'missing',
                'severity' => 'high',
                'title' => 'Permits missing a deceased person record',
                'description' => 'These permits have no linked deceased person. They may have been imported incorrectly. Attach the correct deceased record or delete the permit.',
                'records' => $noDeceased->map(fn ($p) => [
                    'id' => 'miss-dec-'.$p->id,
                    'permit_id' => $p->id,
                    'label' => $p->permit_number,
                    'sub' => 'Applicant: '.($p->applicant_name ?? 'Unknown'),
                    'field_name' => 'deceased_id',
                    'field_value' => null,
                    'edit_url' => route('permits.show', $p),
                ])->toArray(),
            ];
        }

        // ── 4. MISSING: deceased with no date_of_death ──
        $noDod = DeceasedPerson::whereNull('date_of_death')->get();
        if ($noDod->isNotEmpty()) {
            $issues[] = [
                'id' => 'missing-dod',
                'type' => 'missing',
                'severity' => 'medium',
                'title' => 'Deceased records missing date of death',
                'description' => 'Date of death is required for official permits. Fill in the missing date on each record.',
                'records' => $noDod->map(fn ($d) => [
                    'id' => 'miss-dod-'.$d->id,
                    'permit_id' => null,
                    'label' => $d->last_name.', '.$d->first_name,
                    'sub' => 'Record #'.$d->id.' — Added '.$d->created_at->format('M d, Y'),
                    'field_name' => 'date_of_death',
                    'field_value' => null,
                    'edit_url' => route('deceased.show', $d),
                ])->toArray(),
            ];
        }

        // ── 5. MISSING: permits with no applicant name ──
        $noApplicant = BurialPermit::where(function ($q) {
            $q->whereNull('applicant_name')->orWhere('applicant_name', '')->orWhere('applicant_name', 'Unknown');
        })->with('deceased')->get();
        if ($noApplicant->isNotEmpty()) {
            $issues[] = [
                'id' => 'missing-applicant',
                'type' => 'missing',
                'severity' => 'medium',
                'title' => 'Permits with missing or placeholder applicant name',
                'description' => 'The requesting party\'s name is required. Update these permits with the correct applicant information.',
                'records' => $noApplicant->map(fn ($p) => [
                    'id' => 'miss-app-'.$p->id,
                    'permit_id' => $p->id,
                    'label' => $p->permit_number,
                    'sub' => optional($p->deceased)->last_name.', '.optional($p->deceased)->first_name,
                    'field_name' => 'applicant_name',
                    'field_value' => $p->applicant_name ?? null,
                    'edit_url' => route('permits.show', $p),
                ])->toArray(),
            ];
        }

        // ── 6. MISSING: released permits with no expiry date ──
        $noExpiry = BurialPermit::where('status', 'released')->whereNull('expiry_date')->with('deceased')->get();
        if ($noExpiry->isNotEmpty()) {
            $issues[] = [
                'id' => 'missing-expiry',
                'type' => 'missing',
                'severity' => 'medium',
                'title' => 'Released permits with no expiry date set',
                'description' => 'Released permits should always have an expiry date. These were likely released without the system auto-setting one. Review and set expiry dates.',
                'records' => $noExpiry->map(fn ($p) => [
                    'id' => 'miss-exp-'.$p->id,
                    'permit_id' => $p->id,
                    'label' => $p->permit_number,
                    'sub' => optional($p->deceased)->last_name.', '.optional($p->deceased)->first_name.' — Released '.$p->updated_at->format('M d, Y'),
                    'field_name' => 'expiry_date',
                    'field_value' => null,
                    'edit_url' => route('permits.show', $p),
                ])->toArray(),
            ];
        }

        // ── 7. INCONSISTENT: deceased first/last name appears to be swapped ──
        // (last_name looks like a first name — single short word in last, long compound in first)
        $possibleSwapped = DeceasedPerson::whereRaw('LENGTH(last_name) < 3 AND LENGTH(first_name) > 8')->get();
        if ($possibleSwapped->isNotEmpty()) {
            $issues[] = [
                'id' => 'incon-name-swap',
                'type' => 'inconsistent',
                'severity' => 'low',
                'title' => 'Possible first/last name swap in deceased records',
                'description' => 'These records have a very short last name and a long first name, which may indicate the names were entered in the wrong fields.',
                'records' => $possibleSwapped->map(fn ($d) => [
                    'id' => 'swap-'.$d->id,
                    'permit_id' => null,
                    'label' => $d->last_name.', '.$d->first_name,
                    'sub' => 'Record #'.$d->id,
                    'field_name' => 'last_name',
                    'field_value' => $d->last_name,
                    'edit_url' => route('deceased.show', $d),
                ])->toArray(),
            ];
        }

        // ── 8. INCONSISTENT: permit type not in known set ──
        $knownTypes = ['cemented', 'niche_1st', 'niche_2nd', 'niche_3rd', 'niche_4th', 'bone_niches'];
        $badType = BurialPermit::whereNotIn('permit_type', $knownTypes)->with('deceased')->get();
        if ($badType->isNotEmpty()) {
            $issues[] = [
                'id' => 'incon-permit-type',
                'type' => 'inconsistent',
                'severity' => 'low',
                'title' => 'Permits with unrecognised permit type values',
                'description' => 'These permits have a permit_type that does not match any known fee category. They may have been imported with old or incorrect values.',
                'records' => $badType->map(fn ($p) => [
                    'id' => 'badtype-'.$p->id,
                    'permit_id' => $p->id,
                    'label' => $p->permit_number,
                    'sub' => optional($p->deceased)->last_name.', '.optional($p->deceased)->first_name,
                    'field_name' => 'permit_type',
                    'field_value' => $p->permit_type,
                    'edit_url' => route('permits.show', $p),
                ])->toArray(),
            ];
        }

        // ── 9. INCONSISTENT: deceased records with age <= 0 ──
        $badAge = DeceasedPerson::where('age', '<=', 0)->whereNotNull('age')->get();
        if ($badAge->isNotEmpty()) {
            $issues[] = [
                'id' => 'incon-age',
                'type' => 'inconsistent',
                'severity' => 'low',
                'title' => 'Deceased records with invalid age (0 or negative)',
                'description' => 'Age at death should be a positive number. These records likely have a data entry error.',
                'records' => $badAge->map(fn ($d) => [
                    'id' => 'badage-'.$d->id,
                    'permit_id' => null,
                    'label' => $d->last_name.', '.$d->first_name,
                    'sub' => 'Record #'.$d->id,
                    'field_name' => 'age',
                    'field_value' => (string) $d->age,
                    'edit_url' => route('deceased.show', $d),
                ])->toArray(),
            ];
        }

        // ── 10. INCONSISTENT: date_of_death is in the future ──
        $futureDod = DeceasedPerson::whereNotNull('date_of_death')
            ->where('date_of_death', '>', now()->toDateString())->get();
        if ($futureDod->isNotEmpty()) {
            $issues[] = [
                'id' => 'incon-future-dod',
                'type' => 'inconsistent',
                'severity' => 'medium',
                'title' => 'Deceased records with a future date of death',
                'description' => 'Date of death cannot be in the future. These records likely have a data entry error (e.g. wrong year).',
                'records' => $futureDod->map(fn ($d) => [
                    'id' => 'futdod-'.$d->id,
                    'permit_id' => null,
                    'label' => $d->last_name.', '.$d->first_name,
                    'sub' => 'Record #'.$d->id,
                    'field_name' => 'date_of_death',
                    'field_value' => $d->date_of_death->format('Y-m-d'),
                    'edit_url' => route('deceased.show', $d),
                ])->toArray(),
            ];
        }

        return response()->json(['issues' => $issues, 'scanned_at' => now()->toISOString()]);
    }
}

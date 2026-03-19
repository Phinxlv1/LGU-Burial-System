<?php

namespace App\Http\Controllers;

use App\Models\BurialPermit;
use App\Models\DeceasedPerson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BurialPermitController extends Controller
{
    public function index()
    {
        $permits = BurialPermit::with('deceased')->latest()->paginate(15);
        return view('permits.index', compact('permits'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'requestor_name'  => 'required|string|max:255',
            'first_name'      => 'required|string|max:255',
            'last_name'       => 'required|string|max:255',
            'date_of_death'   => 'required|date',
            'burial_fee_type' => 'required|string',
            'nationality'     => 'nullable|string|max:100',
            'age'             => 'nullable|integer|min:0',
            'sex'             => 'nullable|in:Male,Female',
            'kind_of_burial'  => 'nullable|string|max:100',
        ]);

        $deceased = DeceasedPerson::create([
            'first_name'     => $request->first_name,
            'last_name'      => $request->last_name,
            'date_of_death'  => $request->date_of_death,
            'nationality'    => $request->nationality,
            'age'            => $request->age,
            'sex'            => $request->sex,
            'kind_of_burial' => $request->kind_of_burial,
        ]);

        $latest = BurialPermit::whereYear('created_at', now()->year)->count() + 1;
        $permitNumber = 'BP-' . now()->year . '-' . str_pad($latest, 5, '0', STR_PAD_LEFT);

        BurialPermit::create([
            'permit_number'          => $permitNumber,
            'deceased_id'            => $deceased->id,
            'permit_type'            => $request->burial_fee_type,
            'kind_of_burial'         => $request->kind_of_burial,
            'applicant_name'         => $request->requestor_name,
            'applicant_relationship' => $request->requestor_relationship ?? 'N/A',
            'applicant_contact'      => $request->requestor_contact ?? 'N/A',
            'applicant_address'      => $request->requestor_address,
            'status'                 => 'pending',
            'processed_by'           => Auth::id(),
        ]);

        return redirect()->route('permits.index')->with('success', 'Burial permit created successfully.');
    }

    public function show(BurialPermit $permit)
    {
        $permit->load('deceased', 'processedBy');
        return view('permits.show', compact('permit'));
    }

    public function approve(BurialPermit $permit)
    {
        abort_if($permit->status !== 'pending', 403);
        $permit->update([
            'status'       => 'approved',
            'processed_by' => Auth::id(),
            'issued_date'  => now(),
        ]);
        return redirect()->route('permits.show', $permit)->with('success', 'Permit approved successfully.');
    }

    public function release(BurialPermit $permit)
    {
        abort_if($permit->status !== 'approved', 403);
        $permit->update([
            'status'      => 'released',
            'expiry_date' => now()->addYear(),
        ]);
        return redirect()->route('permits.show', $permit)->with('success', 'Permit released successfully.');
    }

    public function print(BurialPermit $permit)
{
    $permit->load('deceased');

    $templatePath = storage_path('app/templates/permit.docx');

    if (!file_exists($templatePath)) {
        abort(500, 'Permit template not found. Please place permit.docx in storage/app/templates/');
    }

    $template = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

    $fees = [
        'cemented'   => ['tomb'=>'910.00',  'permit'=>'20.00','maint'=>'50.00','app'=>'20.00','total'=>'1,000.00'],
        'niche_1st'  => ['tomb'=>'7,960.00','permit'=>'20.00','maint'=>'-',    'app'=>'20.00','total'=>'8,000.00'],
        'niche_2nd'  => ['tomb'=>'6,560.00','permit'=>'20.00','maint'=>'-',    'app'=>'20.00','total'=>'6,600.00'],
        'niche_3rd'  => ['tomb'=>'5,660.00','permit'=>'20.00','maint'=>'-',    'app'=>'20.00','total'=>'5,700.00'],
        'niche_4th'  => ['tomb'=>'5,260.00','permit'=>'20.00','maint'=>'-',    'app'=>'20.00','total'=>'5,300.00'],
        'bone_niches'=> ['tomb'=>'4,960.00','permit'=>'20.00','maint'=>'-',    'app'=>'20.00','total'=>'5,000.00'],
    ];
    $fee  = $fees[$permit->permit_type] ?? $fees['cemented'];
    $type = $permit->permit_type;
    $isC  = $type === 'cemented';
    $isN  = in_array($type, ['niche_1st','niche_2nd','niche_3rd','niche_4th']);
    $isB  = $type === 'bone_niches';
    $expiryDate = $permit->expiry_date ? $permit->expiry_date->format('F d, Y') : now()->addYears(5)->format('F d, Y');
    $expiryYear = $permit->expiry_date ? $permit->expiry_date->format('Y') : now()->addYears(5)->format('Y');

    $template->setValue('permit_no',        $permit->permit_number);
    $template->setValue('reg_no',           $permit->id);
    $template->setValue('year',             $permit->created_at->format('Y'));
    $template->setValue('date',             $permit->created_at->format('F d, Y'));
    $template->setValue('date_applied',     $permit->created_at->format('Y'));
    $template->setValue('date_expired',     $expiryYear);
    $template->setValue('expiry_date',      $expiryDate);
    $template->setValue('renewal_check',    '');
    $template->setValue('new_check',        'X');
    $template->setValue('deceased_name',    optional($permit->deceased)->first_name . ' ' . optional($permit->deceased)->last_name);
    $template->setValue('date_of_death',    optional(optional($permit->deceased)->date_of_death)?->format('F d, Y') ?? '');
    $template->setValue('place_of_death',   optional($permit->deceased)->address ?? 'Carmen, Davao del Norte');
    $template->setValue('age',              optional($permit->deceased)->age ?? '');
    $template->setValue('sex',              optional($permit->deceased)->sex ?? '');
    $template->setValue('nationality',      optional($permit->deceased)->nationality ?? '');
    $template->setValue('applicant_name',   $permit->applicant_name ?? '');
    $template->setValue('relationship',     $permit->applicant_relationship ?? 'Applicant');
    $template->setValue('applicant_address',$permit->applicant_address ?? '');
    $template->setValue('contact',          $permit->applicant_contact ?? '');
    $template->setValue('check_cemented',   $isC ? 'X' : ' ');
    $template->setValue('check_niche',      $isN ? 'X' : ' ');
    $template->setValue('check_bone',       $isB ? 'X' : ' ');
    $template->setValue('fee_tomb',         $fee['tomb']);
    $template->setValue('fee_permit',       $fee['permit']);
    $template->setValue('fee_maint',        $fee['maint']);
    $template->setValue('fee_app',          $fee['app']);
    $template->setValue('fee_total',        $fee['total']);
    $template->setValue('or_number',        $permit->or_number ?? '');
    $template->setValue('paid_on',          $permit->created_at->format('F d, Y'));
    $template->setValue('amount_paid',      $fee['total']);

    $fileName = 'BurialPermit-' . $permit->permit_number . '.docx';
    $tempFile = tempnam(sys_get_temp_dir(), 'permit_');
    $template->saveAs($tempFile);

    return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
}

public function renew(BurialPermit $permit)
{
    abort_if($permit->status !== 'released', 403);
    $permit->update(['status' => 'released', 'expiry_date' => now()->addYears(5)]);
    return redirect()->route('permits.index')->with('success', 'Permit renewed for 5 years.');
}

    public function destroy(BurialPermit $permit)
    {
        $permit->deceased()->delete();
        $permit->delete();
        return redirect()->route('permits.index')->with('success', 'Permit deleted successfully.');
    }

    // Unused stubs kept for resource route compatibility
    public function create() {}
    public function edit(BurialPermit $permit) {}
    public function update(Request $request, BurialPermit $permit) {}
}
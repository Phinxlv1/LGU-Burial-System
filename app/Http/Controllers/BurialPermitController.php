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
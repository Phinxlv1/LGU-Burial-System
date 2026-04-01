<?php

namespace App\Http\Controllers;

use App\Models\DeceasedPerson;
use Illuminate\Http\Request;

class DeceasedPersonController extends Controller
{
    public function index()
    {
        $deceased = DeceasedPerson::withCount('permits')
            ->latest()
            ->paginate(9);

        return view('deceased.index', compact('deceased'));
    }

    public function show(DeceasedPerson $deceased)
    {
        $deceased->load('permits');

        return view('deceased.show', compact('deceased'));
    }

    public function update(Request $request, DeceasedPerson $deceased)
    {
        $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'sex' => 'nullable|in:Male,Female',
            'age' => 'nullable|integer|min:0',
            'civil_status' => 'nullable|string|max:100',
            'nationality' => 'nullable|string|max:100',
            'religion' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'date_of_birth' => 'nullable|date',
            'date_of_death' => 'required|date',
            'place_of_death' => 'nullable|string|max:255',
            'cause_of_death' => 'nullable|string|max:255',
            'kind_of_burial' => 'nullable|in:Ground,Niche,Cremation',
            'name_extension' => 'nullable|string|max:10',
            'name_number' => 'nullable|string|max:20',
            'phone_number' => 'nullable|string|max:20',
        ]);

        $deceased->update($request->only([
            'last_name', 'first_name', 'middle_name', 'sex', 'age',
            'civil_status', 'nationality', 'religion', 'address',
            'date_of_birth', 'date_of_death', 'place_of_death',
            'cause_of_death', 'kind_of_burial',
            'name_extension', 'name_number', 'phone_number',
        ]));

        return redirect()->route('deceased.show', $deceased)
            ->with('success', 'Record updated successfully.');
    }

    public function destroy(DeceasedPerson $deceased)
    {
        $name = "{$deceased->first_name} {$deceased->last_name}";

        \App\Models\ActivityLog::record(
            action: 'deleted',
            modelType: 'DeceasedPerson',
            modelId: $deceased->id,
            modelLabel: $name,
            oldValues: $deceased->toArray(),
            description: "Deceased record for {$name} deleted by " . auth()->user()->name
        );

        $deceased->delete();

        return redirect()->route('deceased.index')
            ->with('success', "Deceased record for {$name} deleted.");
    }

    // Unused stubs required by Route::resource
    public function create() {}

    public function store(Request $request) {}

    public function edit(DeceasedPerson $deceased) {}
}

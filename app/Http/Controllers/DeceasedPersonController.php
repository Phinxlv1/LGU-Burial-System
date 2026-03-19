<?php

namespace App\Http\Controllers;

use App\Models\DeceasedPerson;
use Illuminate\Http\Request;

class DeceasedPersonController extends Controller
{
    public function index()
    {
        $search = request('search');

        $deceased = DeceasedPerson::withCount('permits')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('middle_name', 'like', "%{$search}%")
                      ->orWhereRaw("CONCAT(first_name, ' ', last_name) like ?", ["%{$search}%"])
                      ->orWhereRaw("CONCAT(first_name, ' ', middle_name, ' ', last_name) like ?", ["%{$search}%"]);
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('deceased.index', compact('deceased'));
    }

    public function search()
    {
        $q = request('q');

        $results = DeceasedPerson::where('first_name', 'like', "%{$q}%")
            ->orWhere('last_name', 'like', "%{$q}%")
            ->orWhere('middle_name', 'like', "%{$q}%")
            ->orWhereRaw("CONCAT(first_name, ' ', last_name) like ?", ["%{$q}%"])
            ->orWhereRaw("CONCAT(first_name, ' ', middle_name, ' ', last_name) like ?", ["%{$q}%"])
            ->limit(8)
            ->get()
            ->map(fn($p) => [
                'name' => trim($p->first_name . ' ' . ($p->middle_name ? $p->middle_name . ' ' : '') . $p->last_name),
                'date_of_death' => $p->date_of_death?->format('M d, Y'),
            ]);

        return response()->json($results);
    }

    public function show(DeceasedPerson $deceased)
    {
        $deceased->load('permits');
        return view('deceased.show', compact('deceased'));
    }

    public function destroy(DeceasedPerson $deceased)
    {
        $deceased->permits()->delete();
        $deceased->delete();
        return redirect()->route('deceased.index')->with('success', 'Deceased record deleted.');
    }

    public function create() {}
    public function store(Request $request) {}
    public function edit(DeceasedPerson $deceased) {}
    public function update(Request $request, DeceasedPerson $deceased) {}
}
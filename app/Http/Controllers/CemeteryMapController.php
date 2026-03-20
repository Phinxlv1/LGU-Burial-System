<?php

namespace App\Http\Controllers;

use App\Models\CemeteryPlot;
use App\Models\DeceasedPerson;
use Illuminate\Http\Request;

class CemeteryMapController extends Controller
{
    public function index()
    {
        $stats = [
            'total'     => CemeteryPlot::count(),
            'available' => CemeteryPlot::where('status', 'available')->count(),
            'occupied'  => CemeteryPlot::where('status', 'occupied')->count(),
            'reserved'  => CemeteryPlot::where('status', 'reserved')->count(),
        ];

        return view('cemetery.map', compact('stats'));
    }

    /**
     * Return all plots as GeoJSON for the map.
     */
    public function plots()
    {
        $plots = CemeteryPlot::with('deceased')->get();

        $features = $plots->map(function ($plot) {
            return [
                'type' => 'Feature',
                'geometry' => [
                    'type'        => 'Point',
                    'coordinates' => [$plot->longitude ?? 0, $plot->latitude ?? 0],
                ],
                'properties' => [
                    'id'           => $plot->id,
                    'plot_code'    => $plot->plot_code,
                    'section'      => $plot->section,
                    'row'          => $plot->row,
                    'column'       => $plot->column,
                    'status'       => $plot->status,
                    'notes'        => $plot->notes,
                    'deceased_name'=> $plot->deceased
                                        ? $plot->deceased->first_name . ' ' . $plot->deceased->last_name
                                        : null,
                    'date_of_death'=> $plot->deceased?->date_of_death?->format('M d, Y'),
                ],
            ];
        });

        return response()->json([
            'type'     => 'FeatureCollection',
            'features' => $features,
        ]);
    }

    /**
     * Store a new plot from the map click.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'plot_code'   => 'required|string|max:50|unique:cemetery_plots,plot_code',
            'section'     => 'nullable|string|max:50',
            'row'         => 'nullable|string|max:20',
            'column'      => 'nullable|string|max:20',
            'latitude'    => 'required|numeric|between:-90,90',
            'longitude'   => 'required|numeric|between:-180,180',
            'status'      => 'required|in:available,occupied,reserved',
            'deceased_id' => 'nullable|exists:deceased_persons,id',
            'notes'       => 'nullable|string|max:500',
        ]);

        $plot = CemeteryPlot::create($data);

        return response()->json(['success' => true, 'plot' => $plot->load('deceased')]);
    }

    /**
     * Update plot status / assignment.
     */
    public function update(Request $request, CemeteryPlot $plot)
    {
        $data = $request->validate([
            'status'      => 'sometimes|in:available,occupied,reserved',
            'deceased_id' => 'nullable|exists:deceased_persons,id',
            'notes'       => 'nullable|string|max:500',
            'section'     => 'nullable|string|max:50',
            'row'         => 'nullable|string|max:20',
            'column'      => 'nullable|string|max:20',
        ]);

        $plot->update($data);

        return response()->json(['success' => true, 'plot' => $plot->fresh()->load('deceased')]);
    }

    /**
     * Delete a plot.
     */
    public function destroy(CemeteryPlot $plot)
    {
        $plot->delete();
        return response()->json(['success' => true]);
    }

    /**
     * Search deceased persons for the assign dropdown.
     */
    public function searchDeceased(Request $request)
    {
        $q = $request->get('q', '');
        $deceased = DeceasedPerson::where(function ($query) use ($q) {
            $query->where('first_name', 'like', "%{$q}%")
                  ->orWhere('last_name',  'like', "%{$q}%");
        })
        ->whereDoesntHave('permits', function ($q) {
            // Only deceased with no occupied plot
        })
        ->limit(10)
        ->get(['id', 'first_name', 'last_name', 'date_of_death']);

        return response()->json($deceased->map(fn($d) => [
            'id'   => $d->id,
            'name' => $d->first_name . ' ' . $d->last_name,
            'dod'  => optional($d->date_of_death)->format('M d, Y'),
        ]));
    }
}
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
            'total' => CemeteryPlot::count(),
            'available' => CemeteryPlot::where('status', 'available')->count(),
            'occupied' => CemeteryPlot::where('status', 'occupied')->count(),
            'reserved' => CemeteryPlot::where('status', 'reserved')->count(),
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
            $deceased = $plot->deceased;
            $permit   = $deceased ? $deceased->permits()->latest()->first() : null;

            return [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [(float)$plot->longitude, (float)$plot->latitude],
                ],
                'properties' => [
                    'id'            => $plot->id,
                    'plot_code'     => $plot->plot_code,
                    'section'       => $plot->section,
                    'row'           => $plot->row,
                    'column'        => $plot->column,
                    'status'        => $plot->status, // available, occupied, reserved
                    'permit_status' => $permit ? $permit->status : 'none', // active, expiring, expired
                    'expiry_date'   => $permit ? $permit->expiry_date?->format('Y-m-d') : null,
                    'permit_number' => $permit ? $permit->permit_number : null,
                    'deceased_name' => $deceased ? $deceased->first_name.' '.$deceased->last_name : null,
                    'deceased_id'   => $deceased ? $deceased->id : null,
                    'deceased_age'  => $deceased ? $deceased->age : null,
                    'deceased_sex'  => $deceased ? $deceased->sex : null,
                    'date_of_death' => $deceased?->date_of_death?->format('M d, Y'),
                    'notes'         => $plot->notes,
                ],
            ];
        });

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $features,
        ]);
    }

    /**
     * Store a new plot from the map click.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'plot_code' => 'required|string|max:50|unique:cemetery_plots,plot_code',
            'section' => 'nullable|string|max:50',
            'row' => 'nullable|string|max:20',
            'column' => 'nullable|string|max:20',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'status' => 'required|in:available,occupied,reserved',
            'deceased_id' => 'nullable|exists:deceased_persons,id',
            'notes' => 'nullable|string|max:500',
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
            'status' => 'sometimes|in:available,occupied,reserved',
            'deceased_id' => 'nullable|exists:deceased_persons,id',
            'notes' => 'nullable|string|max:500',
            'section' => 'nullable|string|max:50',
            'row' => 'nullable|string|max:20',
            'column' => 'nullable|string|max:20',
        ]);

        $plot->update($data);

        return response()->json(['success' => true, 'plot' => $plot->fresh()->load('deceased')]);
    }

    /**
     * Delete a plot.
     */
    public function destroy(CemeteryPlot $plot)
    {
        $label = $plot->plot_code;

        \App\Models\ActivityLog::record(
            action: 'deleted',
            modelType: 'CemeteryPlot',
            modelId: $plot->id,
            modelLabel: $label,
            oldValues: $plot->toArray(),
            description: "Cemetery plot {$label} deleted by " . auth()->user()->name
        );

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
                ->orWhere('last_name', 'like', "%{$q}%");
        })
            ->whereDoesntHave('permits', function ($q) {
                // Only deceased with no occupied plot
            })
            ->limit(10)
            ->get(['id', 'first_name', 'last_name', 'date_of_death']);

        return response()->json($deceased->map(fn ($d) => [
            'id' => $d->id,
            'name' => $d->first_name.' '.$d->last_name,
            'dod' => optional($d->date_of_death)->format('M d, Y'),
        ]));
    }

    /**
     * Search burial permits by name
     */
    public function searchPermits(Request $request)
    {
        $q = $request->get('q', '');
        $unassignedOnly = $request->has('unassigned');

        // Allow 1 character search. If empty and unassigned requested, show initial list.
        if (empty($q) && !$unassignedOnly) {
            return response()->json([]);
        }

        $query = \App\Models\BurialPermit::with(['deceased']);

        if (!empty($q)) {
            $query->whereHas('deceased', function ($query) use ($q) {
                $query->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$q}%"])
                      ->orWhereRaw("CONCAT(last_name, ', ', first_name) LIKE ?", ["%{$q}%"])
                      ->orWhere('first_name', 'like', "%{$q}%")
                      ->orWhere('last_name', 'like', "%{$q}%");
            });
        }

        $permits = $query->latest()->limit(50)->get();

        $nicheAssignedIds = [];
        if ($unassignedOnly) {
            // Get all deceased_ids currently assigned in any Niche Grid
            $nicheAssignedIds = collect(\App\Models\CemeteryGrid::pluck('cells'))
                ->flatMap(function($cells) {
                    return collect($cells ?? [])->pluck('deceased_id');
                })
                ->filter()
                ->unique()
                ->toArray();
        }

        $results = $permits->map(function ($permit) {
            // Check legacy plot first
            $plot = \App\Models\CemeteryPlot::where('deceased_id', $permit->deceased_id)->first();
            
            // NEW: Check Niche Grids
            $gridMatch = null;
            $allGrids = \App\Models\CemeteryGrid::all();
            foreach ($allGrids as $grid) {
                if ($grid->cells && (is_array($grid->cells) || is_object($grid->cells))) {
                    foreach ($grid->cells as $cell) {
                        if (isset($cell['deceased_id']) && $cell['deceased_id'] == $permit->deceased_id) {
                            $gridMatch = $grid;
                            break 2;
                        }
                    }
                }
            }

            return [
                'id' => $permit->id,
                'permit_id' => $permit->id,
                'plot_code' => ($plot && $plot->plot_code) ? $plot->plot_code : ($gridMatch ? $gridMatch->name : 'Unassigned'),
                'deceased_name' => $permit->deceased ? $permit->deceased->first_name . ' ' . $permit->deceased->last_name : $permit->deceased_name,
                'permit_status' => $permit->status,
                'permit_number' => $permit->permit_number,
                'deceased_id' => $permit->deceased_id,
                'has_plot' => ($plot || $gridMatch) ? true : false,
                'grid_id' => $gridMatch ? $gridMatch->id : null,
                'latitude' => $gridMatch ? $gridMatch->latitude : ($plot ? (float)$plot->latitude : null),
                'longitude' => $gridMatch ? $gridMatch->longitude : ($plot ? (float)$plot->longitude : null),
            ];
        });

        if ($unassignedOnly) {
            $results = $results->filter(function($res) use ($nicheAssignedIds) {
                if ($res['has_plot']) return false;
                if (in_array($res['deceased_id'], $nicheAssignedIds)) return false;
                return true;
            });
        }

        return response()->json($results->values()->take(15));
    }
}

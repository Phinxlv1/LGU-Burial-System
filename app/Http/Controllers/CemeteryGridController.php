<?php

namespace App\Http\Controllers;

use App\Models\CemeteryGrid;
use Illuminate\Http\Request;

class CemeteryGridController extends Controller
{
    public function index()
    {
        return response()->json(CemeteryGrid::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string',
            'rows'         => 'required|integer|min:1',
            'cols'         => 'required|integer|min:1',
            'label_format' => 'required|string',
            'latitude'     => 'nullable|numeric',
            'longitude'    => 'nullable|numeric',
            'start_lat'    => 'nullable|numeric',
            'start_lng'    => 'nullable|numeric',
            'end_lat'      => 'nullable|numeric',
            'end_lng'      => 'nullable|numeric',
            'color'        => 'nullable|string',
            'rotation'     => 'nullable|numeric',
            'width_scale'  => 'nullable|numeric',
            'cells'        => 'nullable|array',
        ]);

        // Derive midpoint lat/lng from line endpoints if not explicitly supplied
        if (!isset($validated['latitude']) && isset($validated['start_lat'], $validated['end_lat'])) {
            $validated['latitude']  = ($validated['start_lat'] + $validated['end_lat']) / 2;
            $validated['longitude'] = ($validated['start_lng'] + $validated['end_lng']) / 2;
        }

        $grid = CemeteryGrid::create($validated);

        return response()->json($grid, 201);
    }

    public function update(Request $request, CemeteryGrid $niche_grid)
    {
        $validated = $request->validate([
            'name'         => 'sometimes|string',
            'rows'         => 'sometimes|integer|min:1',
            'cols'         => 'sometimes|integer|min:1',
            'label_format' => 'sometimes|string',
            'latitude'     => 'nullable|numeric',
            'longitude'    => 'nullable|numeric',
            'start_lat'    => 'nullable|numeric',
            'start_lng'    => 'nullable|numeric',
            'end_lat'      => 'nullable|numeric',
            'end_lng'      => 'nullable|numeric',
            'color'        => 'nullable|string',
            'rotation'     => 'nullable|numeric',
            'width_scale'  => 'nullable|numeric',
            'cells'        => 'sometimes|array',
        ]);

        $niche_grid->update($validated);

        return response()->json($niche_grid);
    }

    public function destroy(CemeteryGrid $niche_grid)
    {
        $niche_grid->delete();
        return response()->json(['message' => 'Grid deleted successfully.']);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeceasedPerson extends Model
{
    use HasFactory;

    protected $table = 'deceased_persons';

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'name_extension',
        'name_number',
        'phone_number',
        'date_of_birth',
        'date_of_death',
        'nationality',
        'age',
        'sex',
        'civil_status',
        'religion',
        'kind_of_burial',
        'place_of_death',
        'cause_of_death',
        'address',
        'age_at_death',
    ];

    protected $casts = [
        'date_of_death' => 'date',
    ];

    public function getFullNameAttribute(): string
    {
        $fullName = $this->last_name . ', ' . $this->first_name;
        if ($this->middle_name) {
            $fullName .= ' ' . strtoupper(substr($this->middle_name, 0, 1)) . '.';
        }
        if ($this->name_extension) {
            $fullName .= ' ' . $this->name_extension;
        }
        return $fullName;
    }

    public function permits()
    {
        return $this->hasMany(BurialPermit::class, 'deceased_id');
    }

    /**
     * Determine if and where the deceased is assigned on the map.
     */
    public function getAssignedLocation()
    {
        // 1. Check Legacy Plots
        $plot = CemeteryPlot::where('deceased_id', $this->id)->first();
        if ($plot) {
            return [
                'type'  => 'plot',
                'label' => $plot->plot_code,
                'lat'   => (float)$plot->latitude,
                'lng'   => (float)$plot->longitude,
            ];
        }

        // 2. Check Niche Grids
        $grids = CemeteryGrid::all();
        foreach ($grids as $grid) {
            if ($grid->cells && (is_array($grid->cells) || is_object($grid->cells))) {
                foreach ($grid->cells as $cell) {
                    if (isset($cell['deceased_id']) && $cell['deceased_id'] == $this->id) {
                        return [
                            'type'  => 'grid',
                            'label' => $grid->name . ' (' . ($cell['label'] ?? 'Niche') . ')',
                            'lat'   => (float)$grid->latitude,
                            'lng'   => (float)$grid->longitude,
                            'grid_id' => $grid->id,
                        ];
                    }
                }
            }
        }

        return null;
    }
}

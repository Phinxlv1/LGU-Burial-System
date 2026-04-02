<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CemeteryGrid extends Model
{
    protected $table = 'niche_grids';

    protected $fillable = [
        'name',
        'rows',
        'cols',
        'label_format',
        'latitude',
        'longitude',
        'start_lat',
        'start_lng',
        'end_lat',
        'end_lng',
        'color',
        'rotation',
        'width_scale',
        'cells',
    ];

    protected $casts = [
        'cells'     => 'array',
        'latitude'  => 'float',
        'longitude' => 'float',
        'start_lat' => 'float',
        'start_lng' => 'float',
        'end_lat'   => 'float',
        'end_lng'   => 'float',
    ];
}

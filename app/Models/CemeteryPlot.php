<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CemeteryPlot extends Model
{
    use HasFactory;

    protected $table = 'cemetery_plots';

    protected $fillable = [
        'plot_code',
        'section',
        'row',
        'column',
        'latitude',
        'longitude',
        'status',
        'deceased_id',
        'notes',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function deceased()
    {
        return $this->belongsTo(DeceasedPerson::class, 'deceased_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeceasedPerson extends Model
{
    use HasFactory;

    protected $table = 'deceased_persons';

    protected $fillable = [
    'first_name',
    'middle_name',
    'last_name',
    'date_of_death',
    'nationality',
    'age',
    'sex',
    'kind_of_burial',
];

    protected $casts = [
        'date_of_death' => 'date',
    ];

    public function permits()
    {
        return $this->hasMany(BurialPermit::class, 'deceased_id');
    }
}
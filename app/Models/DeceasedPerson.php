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

    public function permits()
    {
        return $this->hasMany(BurialPermit::class, 'deceased_id');
    }
}

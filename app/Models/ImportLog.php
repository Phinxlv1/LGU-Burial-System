<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportLog extends Model
{
    protected $fillable = [
        'file_name',
        'uploaded_by',
        'total_rows',
        'imported',
        'skipped',
        'skip_reasons',
    ];

    protected $casts = [
        'skip_reasons' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
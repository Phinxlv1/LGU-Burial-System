<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportLog extends Model
{
    protected $fillable = [
        'filename',
        'total_rows',
        'imported',
        'skipped',
        'skipped_details',
        'imported_by',
    ];

    protected $casts = [
        'skipped_details' => 'array',
    ];

    public function importedBy()
    {
        return $this->belongsTo(User::class, 'imported_by');
    }
}
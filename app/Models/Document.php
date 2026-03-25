<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'permit_id',
        'file_name',
        'file_path',
        'file_type',
        'mime_type',
        'uploaded_by',
    ];

    public function permit()
    {
        return $this->belongsTo(BurialPermit::class, 'permit_id');
    }

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}

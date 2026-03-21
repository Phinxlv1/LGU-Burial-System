<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsNotification extends Model
{
    protected $fillable = [
        'permit_id',
        'recipient_number',
        'message',
        'status',
        'type',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function permit()
    {
        return $this->belongsTo(BurialPermit::class, 'permit_id');
    }
}
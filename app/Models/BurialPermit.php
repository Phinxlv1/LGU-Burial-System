<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BurialPermit extends Model
{
    protected $fillable = [
        'permit_number',
        'deceased_id',
        'permit_type',
        'kind_of_burial',
        'applicant_name',
        'applicant_relationship',
        'applicant_contact',
        'applicant_address',
        'issued_date',
        'expiry_date',
        'status',
        'remarks',
        'processed_by',
        'issued_by',
        'renewal_count',
    ];

    protected $casts = [
        'issued_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function deceased()
    {
        return $this->belongsTo(DeceasedPerson::class, 'deceased_id');
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'permit_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'permit_id');
    }

    public function smsNotifications()
    {
        return $this->hasMany(SmsNotification::class, 'permit_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BurialPermit extends Model
{
    protected $fillable = [
        'permit_number', 'deceased_id', 'applicant_name',
        'applicant_relationship', 'applicant_contact',
        'applicant_address', 'issued_date', 'expiry_date',
        'status', 'remarks', 'processed_by'
    ];

    public function deceased()
    {
        return $this->belongsTo(DeceasedPerson::class);
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

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($permit) {
            $year = date('Y');
            $count = self::whereYear('created_at', $year)->count() + 1;
            $permit->permit_number = $year . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
        });
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'donor_id',
        'recipient_id',
        'hospital_id',
        'donation_date',
        'blood_bag_serial_number'
    ];

    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }

    public function recipient()
    {
        return $this->belongsTo(Recipient::class);
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }
}

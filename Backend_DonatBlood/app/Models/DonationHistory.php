<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'donor_id', 'donation_request_id', 'blood_type', 'quantity_donated', 'donation_date'
    ];

    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }

    public function donationRequest()
    {
        return $this->belongsTo(DonationRequest::class);
    }
}
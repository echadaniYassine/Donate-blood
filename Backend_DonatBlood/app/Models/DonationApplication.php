<?php

// app/Models/DonationApplication.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'donor_id', 
        'donation_request_id', 
        'status', 
        'appointment_date',
    ];

    protected $casts = [
        'appointment_date' => 'datetime',
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

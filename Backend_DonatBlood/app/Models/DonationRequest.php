<?php

// app/Models/DonationRequest.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'hospital_id',
        'blood_type_needed',
        'quantity_needed',
        'status',
        'location',
        'posted_at',
    ];

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function donationApplications()
    {
        return $this->hasMany(DonationApplication::class);
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'recipient');
    }
    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }
}

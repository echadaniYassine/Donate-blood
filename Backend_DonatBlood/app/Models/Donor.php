<?php

// app/Models/Donor.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'cin', 'blood_type', 'last_donation_date', 'availability', 'email', 'phone' // Include email and phone
    ];

    protected $casts = [
        'last_donation_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function donationApplications()
    {
        return $this->hasMany(DonationApplication::class);
    }

    public function donationHistories()
    {
        return $this->hasMany(DonationHistory::class);
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'recipient');
    }
}

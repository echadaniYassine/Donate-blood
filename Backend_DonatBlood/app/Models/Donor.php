<?php

// app/Models/Donor.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'email', 'phone', 'CIN', 'blood_type', 'last_donation_date',
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

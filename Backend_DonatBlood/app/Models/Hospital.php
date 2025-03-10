<?php

// app/Models/Hospital.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'location', 'name'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function donationRequests()
    {
        return $this->hasMany(DonationRequest::class);
    }

    public function bloodStock()
    {
        return $this->hasMany(BloodStock::class);
    }
}

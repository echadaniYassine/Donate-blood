<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'contact_number'
    ];

    public function donations()
    {
        return $this->hasMany(DonationHistory::class); // ✅ FIXED: Added relationship
    }
}

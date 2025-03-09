<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationHistory extends Model {
    use HasFactory;

    protected $fillable = ['donor_id', 'hospital_id', 'donation_date', 'volume_donated', 'status'];

    public function donor() {
        return $this->belongsTo(Donor::class);
    }

    public function hospital() {
        return $this->belongsTo(Hospital::class);
    }
}

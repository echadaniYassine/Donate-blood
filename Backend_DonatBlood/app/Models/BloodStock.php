<?php

// app/Models/BloodStock.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'hospital_id', 'blood_type', 'quantity', 'last_updated',
    ];

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }
}

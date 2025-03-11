<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',  // The recipient of the notification
        'message',  // The notification message
        'type',     // Type of notification (appointment, success, request, etc.)
        'is_read',  // Read status
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}

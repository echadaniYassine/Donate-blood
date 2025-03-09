<?php

// app/Http/Controllers/NotificationController.php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function sendNotification(Request $request)
    {
        // Validate notification data
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string',
            'type' => 'required|string',
        ]);

        $notification = Notification::create([
            'user_id' => $request->user_id,
            'message' => $request->message,
            'type' => $request->type,
        ]);

        return response()->json(['message' => 'Notification sent successfully', 'notification' => $notification]);
    }

    public function viewNotifications($userId)
    {
        $notifications = Notification::where('user_id', $userId)->get();

        return response()->json(['notifications' => $notifications]);
    }
}

<?php

// app/Http/Controllers/NotificationController.php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get all notifications for the authenticated user.
     */
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())->get();

        return response()->json(['notifications' => $notifications]);
    }
}

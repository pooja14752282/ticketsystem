<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    // GET /notifications — returns latest 10 notifications as JSON
   public function index()
{
    $user = auth()->user();

    $notifications = Notification::where('user_id', $user->uid)
        ->latest()
        ->take(10)
        ->get(['id', 'title', 'message', 'type', 'ticket_id', 'is_read', 'created_at']);

    return response()->json([
        'notifications' => $notifications,
    ]);
}

public function markAllRead()
{
    $user = auth()->user();

    Notification::where('user_id', $user->uid)
        ->where('is_read', false)
        ->update(['is_read' => true]);

    return response()->json(['success' => true]);
}
}
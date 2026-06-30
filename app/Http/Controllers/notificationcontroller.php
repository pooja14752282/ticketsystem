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

        $notifications = Notification::where('uid', $user->uid)
            ->latest()
            ->take(10)
            ->get(['id', 'title', 'message', 'click_action', 'created_at']);

        return response()->json([
            'notifications' => $notifications,
        ]);
    }
}
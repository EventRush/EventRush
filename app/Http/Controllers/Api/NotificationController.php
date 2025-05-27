<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    //
    public function index()
    {
        $user = Auth::user();
        $notifications = $user->unreadNotifications;

        return response()->json([
            'notifications' => $notifications
        ]);
    }

    public function markAsRead($notId)
    {
        $user = auth()->user();
        $notification = $user->unreadNotifications->find($notId);

        if ($notification) {
            $notification->markAsRead();
            return response()->json(['message' => 'Notification marquée comme lue']);
        }

        return response()->json(['message' => 'Notification introuvable'],404);
    }

}

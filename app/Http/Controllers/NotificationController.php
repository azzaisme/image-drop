<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->unreadNotifications->map(function ($n) {
            $notification = [];
            $notification['error'] = $n->data['error'] ?? null;
            $notification['path'] = $n->data['path'] ?? null;
            $notification['id'] = $n->id;
            return $notification;
        });

        return response()->json($notifications->values());
    }

    public function destroy(String $id)
    {

        $notification = Auth::user()->notifications()->findOrFail($id);

        $notification->markAsRead();

        return response()->json([
            'status' => 1,
        ]);
    }
}

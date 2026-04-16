<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        return response()->json([
            'non_lues' => $request->user()->unreadNotifications()->count(),
            'notifications' => $request->user()->notifications()->latest()->take(30)->get(),
        ]);
    }

    public function marquerLue(Request $request, string $id)
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json(['message' => 'Notification lue.']);
    }

    public function toutesLues(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();

        return response()->json(['message' => 'Toutes les notifications marquees comme lues.']);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function read(Request $request, DatabaseNotification $notification): JsonResponse|RedirectResponse
    {
        abort_unless(
            $notification->notifiable_id === $request->user()->id
            && $notification->notifiable_type === get_class($request->user()),
            403
        );

        $notification->markAsRead();

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'unread_count' => $request->user()->unreadNotifications()->count(),
            ]);
        }

        return back();
    }

    public function readAll(Request $request): RedirectResponse
    {
        $request->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'Notifications marked as read.');
    }
}

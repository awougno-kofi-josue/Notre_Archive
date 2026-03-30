<?php

namespace App\Http\Controllers;

use App\Models\UserNotification;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $notifications = UserNotification::query()
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(15);

        return view('notifications.index', compact('notifications'));
    }

    public function markRead(Request $request, UserNotification $notification): RedirectResponse
    {
        abort_if((int) $notification->user_id !== (int) $request->user()->id, 403);

        $notification->is_read = true;
        $notification->save();

        return redirect()->back();
    }

    public function markAllRead(Request $request): RedirectResponse
    {
        UserNotification::query()
            ->where('user_id', $request->user()->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return redirect()->back()->with('success', 'Toutes les notifications ont ete marquees comme lues.');
    }
}

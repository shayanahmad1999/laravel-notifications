<?php

namespace App\Http\Controllers;

use App\Notifications\NewMessageNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications;
        return view('notifications.index', compact('notifications'));
    }

    public function unread()
    {
        $notifications = Auth::user()->unreadNotifications;
        return view('notifications.index', compact('notifications'));
    }

    public function read()
    {
        $notifications = auth()->user()->notifications()->whereNotNull('read_at')->latest()->get();

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return redirect()->back()->with('toast', 'Notification marked as read!');
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return redirect()->back()->with('toast', 'All notifications marked as read!');
    }

    public function markAsUnread($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->update(['read_at' => null]);

        return redirect()->back()->with('toast', 'Notification marked as unread!');
    }

    public function markAllAsUnread()
    {
        Auth::user()->notifications()->update(['read_at' => null]);
        return redirect()->back()->with('toast', 'All notifications marked as unread!');
    }

    public function sendNotification(Request $request)
    {
        $user = Auth::user();
        $user->notify(new NewMessageNotification('This is a test notification from the button.'));

        return redirect()->back()->with('toast', 'Notification sent!');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show a listing of the unread notifications for the current user.
     */
    public function index()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->orderBy('created_at', 'desc')->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Show a single notification and mark it as read, redirecting to related inventory if present.
     */
    public function show($id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->where('id', $id)->firstOrFail();
        /** @var \Illuminate\Notifications\DatabaseNotification $notification */
        if (is_null($notification->read_at) && method_exists($notification, 'markAsRead')) {
                $notification->markAsRead();
            
        }

        $data = $notification->data ?? [];
        if (! empty($data['inventory_id']) && is_numeric($data['inventory_id'])) {
            return redirect()->route('inventories.show', ['inventory' => (int) $data['inventory_id']]);
        }

        return redirect()->back();
    }

    /**
     * Mark a single notification as read (POST).
     */
    public function read($id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->where('id', $id)->firstOrFail();

        /** @var \Illuminate\Notifications\DatabaseNotification $notification */
        if (is_null($notification->read_at) && method_exists($notification, 'markAsRead')) {
                $notification->markAsRead();
            
        }

        return redirect()->back()->with('status', __('ui.notifications.marked_read'));
    }

    /**
     * Mark a single notification as unread (PUT).
     */
    public function unread($id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->where('id', $id)->firstOrFail();

        /** @var \Illuminate\Notifications\DatabaseNotification $notification */
        if (! is_null($notification->read_at)) {
            $notification->read_at = null;
            $notification->save();
        }

        return redirect()->back()->with('status', __('ui.notifications.marked_unread'));
    }

    /**
     * Mark all unread notifications as read (POST).
     */
    public function readAll()
    {
        $user = Auth::user();

        // Use the unreadNotifications() relation to fetch unread notifications
        // (avoids accessing a dynamic property which some analyzers flag as undefined).
        $unread = $user->unreadNotifications()->get();

        /** @var \Illuminate\Notifications\DatabaseNotification $n */
        foreach ($unread as $n) {
            if (method_exists($n, 'markAsRead')) {
                $n->markAsRead();
            }
        }

        return redirect()->back()->with('status', __('ui.notifications.marked_all_read'));
    }
}

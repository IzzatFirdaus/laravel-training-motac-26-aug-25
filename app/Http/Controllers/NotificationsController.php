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

        if (is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        $data = $notification->data ?? [];
        if (! empty($data['inventory_id'])) {
            return redirect()->route('inventories.show', $data['inventory_id']);
        }

        return redirect()->back();
    }
}

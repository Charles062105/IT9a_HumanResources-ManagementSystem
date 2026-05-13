<?php

namespace App\Http\Controllers;

use App\Models\HrmsNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $query = HrmsNotification::latest();

        if ($t = $request->type) {
            $query->where('type', $t);
        }
        if ($r = $request->read) {
            if ($r === 'unread') {
                $query->where('is_read', false);
            }
            if ($r === 'read') {
                $query->where('is_read', true);
            }
        }

        $notifications = $query->paginate(25)->appends($request->all());

        return view('notifications.index', compact('notifications'));
    }

    public function create()
    {
        return view('notifications.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:success,warning,error,info',
        ]);

        HrmsNotification::create($data + ['is_read' => false]);

        return redirect()->route('notifications.index')
            ->with('success', 'Notification sent.');
    }

    public function markRead(HrmsNotification $notification)
    {
        $notification->update(['is_read' => true]);

        return back();
    }

    public function readAll()
    {
        HrmsNotification::where('is_read', false)->update(['is_read' => true]);

        return back()->with('success', 'All notifications marked as read.');
    }

    public function destroy(HrmsNotification $notification)
    {
        $notification->delete();

        return back()->with('success', 'Notification deleted.');
    }
}

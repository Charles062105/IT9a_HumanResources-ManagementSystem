<?php

namespace App\Http\Controllers;

use App\Models\HrmsNotification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        /** @var Builder $query */
        $query = HrmsNotification::where(function ($q) {
            // Show notifications for current user OR system-wide (null user_id)
            $q->where('user_id', Auth::id())
                ->orWhereNull('user_id');
        })->latest('created_at');

        if ($t = $request->input('type')) {
            if ($t === 'danger') {
                $t = 'error';
            }

            $query->where('type', $t);
        }

        if ($r = $request->input('read')) {
            if ($r === 'unread') {
                $query->where('is_read', '=', false);
            } elseif ($r === 'read') {
                $query->where('is_read', '=', true);
            }
        }

        $notifications = $query->paginate(25)->appends($request->all());
        /** @var Builder $unreadQuery */
        $unreadQuery = HrmsNotification::where(function ($q) {
            $q->where('user_id', Auth::id())
                ->orWhereNull('user_id');
        });
        $hasUnread = $unreadQuery->where('is_read', false)->exists();

        return view('notifications.index', compact('notifications', 'hasUnread'));
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
            'type' => 'required|in:success,warning,error,info,danger',
        ]);

        if ($data['type'] === 'danger') {
            $data['type'] = 'error';
        }

        HrmsNotification::create($data + ['is_read' => false]);

        return redirect()->route('notifications.index')
            ->with('success', 'Notification sent.');
    }

    public function markRead(HrmsNotification $notification)
    {
        $notification->update(['is_read' => true]);

        return back();
    }

    public function update(HrmsNotification $notification)
    {
        $notification->update(['is_read' => true]);

        return back();
    }

    public function readAll()
    {
        HrmsNotification::where(function ($q) {
            $q->where('user_id', Auth::id())
                ->orWhereNull('user_id');
        })->where('is_read', false)->update(['is_read' => true]);

        return back()->with('success', 'All notifications marked as read.');
    }

    public function destroy(HrmsNotification $notification)
    {
        $notification->delete();

        return back()->with('success', 'Notification deleted.');
    }
}

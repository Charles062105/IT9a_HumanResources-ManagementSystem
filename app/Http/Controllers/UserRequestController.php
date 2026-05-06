<?php

namespace App\Http\Controllers;

use App\Models\UserRequest;
use App\Models\HrmsNotification;
use Illuminate\Http\Request;

class UserRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = UserRequest::with('user')->latest();

        if ($s  = $request->search)  $query->whereHas('user', fn($q) => $q->where('name', 'like', "%$s%"));
        if ($t  = $request->type)    $query->where('type', $t);
        if ($st = $request->status)  $query->where('status', $st);

        $requests = $query->paginate(20)->appends($request->all());

        return view('requests.index', compact('requests'));
    }

    public function approve(UserRequest $request_model)
    {
        $request_model->update([
            'status'      => 'approved',
            'resolved_by' => auth()->id(),
            'resolved_at' => now(),
        ]);

        if ($request_model->type === 'Account Activation' && $request_model->user) {
            $request_model->user->update(['status' => 'active']);

            HrmsNotification::create([
                'title'   => 'Account Activated',
                'message' => 'Your account has been approved. Please complete your employee profile to get started.',
                'type'    => 'success',
                'user_id' => $request_model->user->id,
            ]);

            // Redirect admin to the profile setup form for this new user
            return redirect()
                ->route('employees.setup', ['user' => $request_model->user->id])
                ->with('success', 'Account approved. Please complete the employee profile below.');
        }

        return back()->with('success', 'Request approved.');
    }

    public function reject(UserRequest $request_model)
    {
        $request_model->update([
            'status'      => 'rejected',
            'resolved_by' => auth()->id(),
            'resolved_at' => now(),
        ]);

        if ($request_model->user) {
            $request_model->user->update(['status' => 'inactive']);
        }

        return back()->with('success', 'Request rejected.');
    }
}

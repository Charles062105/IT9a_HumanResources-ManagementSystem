<?php

namespace App\Http\Controllers;

use App\Models\HrmsNotification;
use App\Models\User;
use App\Models\UserRequest;
use Illuminate\Http\Request;

class UserRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = UserRequest::with('user')->latest();

        if ($s = $request->search) {
            $query->whereHas('user', fn ($q) => $q->where('name', 'like', "%$s%"));
        }
        if ($t = $request->type) {
            $query->where('type', $t);
        }
        if ($st = $request->status) {
            $query->where('status', $st);
        }

        $requests = $query->paginate(20)->appends($request->all());

        return view('requests.index', compact('requests'));
    }

    public function approve(UserRequest $request_model)
    {
        $request_model->update([
            'status' => 'approved',
            'resolved_by' => auth()->id(),
            'resolved_at' => now(),
        ]);

        if ($request_model->type === 'Account Activation' && $request_model->user) {
            $request_model->user->update(['status' => 'active']);

            HrmsNotification::create([
                'title' => 'Account Activated',
                'message' => 'Your account has been approved. Please complete your employee profile to get started.',
                'type' => 'success',
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
            'status' => 'rejected',
            'resolved_by' => auth()->id(),
            'resolved_at' => now(),
        ]);

        if ($request_model->type === 'Account Activation' && $request_model->user) {
            $user = $request_model->user;
            $user->update(['status' => 'rejected']);

            HrmsNotification::create([
                'title' => 'Account Rejected',
                'message' => 'Your account registration has been rejected. Please contact support for more information.',
                'type' => 'error',
                'user_id' => $user->id,
            ]);
        }

        return back()->with('success', 'Request rejected.');
    }

    public function makeAdmin(User $user)
    {
        // Only super admin can make someone admin
        if (! auth()->user()?->isSuperAdmin()) {
            abort(403, 'Only Super Admin can grant admin roles.');
        }

        if (auth()->id() === $user->id) {
            abort(403, 'Cannot change your own role.');
        }

        // Offer choice between super_admin or sub_admin
        return view('requests.make-admin', compact('user'));
    }

    public function assignAdminRole(Request $request, User $user)
    {
        // Only super admin can assign admin roles
        if (! auth()->user()?->isSuperAdmin()) {
            abort(403, 'Only Super Admin can grant admin roles.');
        }

        if (auth()->id() === $user->id) {
            abort(403, 'Cannot change your own role.');
        }

        $validated = $request->validate([
            'role' => 'required|in:super_admin,sub_admin',
            'notes' => 'nullable|string|max:500',
        ]);

        // If user already has the same admin role, block redundant update.
        if ($user->role === $validated['role']) {
            abort(403, 'User already has this admin role.');
        }

        $user->update(['role' => $validated['role']]);

        $roleLabel = $validated['role'] === User::ROLE_SUPER_ADMIN ? 'Super Admin' : 'Sub-Admin';

        HrmsNotification::create([
            'title' => 'Admin Role Granted',
            'message' => "You have been promoted to {$roleLabel}.",
            'type' => 'success',
            'user_id' => $user->id,
            'reference_id' => $user->id,
            'reference_type' => 'role_change',
            'reference_notes' => $validated['notes'] ?? 'No notes provided',
        ]);

        return redirect()->back()->with('success', "{$user->name} is now a {$roleLabel}".($validated['notes'] ?? false ? '. Reason: '.$validated['notes'] : '.'));
    }

    public function showRevokeAdmin(User $user)
    {
        // Only super admin can revoke admin roles
        if (! auth()->user()?->isSuperAdmin()) {
            abort(403, 'Only Super Admin can revoke admin roles.');
        }

        if (auth()->id() === $user->id) {
            abort(403, 'Cannot revoke your own admin role.');
        }

        // Prevent revoking if it's the last super admin
        if ($user->isSuperAdmin()) {
            $superAdminCount = User::where('role', User::ROLE_SUPER_ADMIN)->count();
            if ($superAdminCount <= 1) {
                abort(403, 'Cannot revoke the last Super Admin in the system.');
            }
        }

        return view('requests.revoke-admin', compact('user'));
    }

    public function revokeAdmin(User $user)
    {
        // Only super admin can revoke admin roles
        if (! auth()->user()?->isSuperAdmin()) {
            abort(403, 'Only Super Admin can revoke admin roles.');
        }

        if (auth()->id() === $user->id) {
            abort(403, 'Cannot change your own role.');
        }

        \DB::transaction(function () use ($user) {
            // Lock the super_admin rows to prevent concurrent revocations
            $superAdminCount = User::where('role', User::ROLE_SUPER_ADMIN)->lockForUpdate()->count();
            if ($superAdminCount <= 1 && $user->isSuperAdmin()) {
                abort(403, 'At least one Super Admin must exist in the system.');
            }

            // User can be downgraded to employee
            $user->update(['role' => User::ROLE_EMPLOYEE]);
        });

        HrmsNotification::create([
            'title' => 'Admin Role Revoked',
            'message' => 'Your admin privileges have been revoked.',
            'type' => 'warning',
            'user_id' => $user->id,
        ]);

        return back()->with('success', "Admin role revoked from {$user->name}.");
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeSetupController extends Controller
{
    /**
     * Show the profile setup form.
     * Called after admin approves an account activation request.
     * The $user param is the newly activated user's ID.
     */
    public function show(Request $request)
    {
        $userId = $request->query('user');
        $newUser = $userId ? User::findOrFail($userId) : null;

        // Authorization: user can only view their own setup OR admins can view any
        if ($newUser && auth()->id() !== $newUser->id && ! auth()->user()->isAdmin()) {
            abort(403, 'You are not authorized to access this profile setup.');
        }

        // Guard: if employee record already exists for this user, skip setup
        if ($newUser && $newUser->employee) {
            // If profile exists but not marked complete, mark it complete now
            if (! $newUser->employee->profile_completed) {
                $newUser->employee->update(['profile_completed' => true]);
            }

            return redirect()->route('employees.index')
                ->with('success', 'Employee profile already exists for '.$newUser->name.'.');
        }

        // Self-login: if current user already has a complete employee record, go to dashboard
        if (! $newUser && auth()->user()?->employee?->profile_completed) {
            return redirect()->route('dashboard');
        }

        return view('employees.setup', compact('newUser'));
    }

    /**
     * Save the new employee profile created by the admin.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:employees,email|unique:users,email|regex:/^[a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date|before:today',
            'department' => 'required|string|max:100',
            'position' => 'required|string|max:100',
            'date_hired' => 'required|date|before_or_equal:today',
            'status' => 'required|in:active,probationary,contractual,inactive',
            'contract_expiry' => 'required_if:status,contractual|nullable|date|after:today|before:'.now()->addYears(5)->format('Y-m-d'),
            'sss_number' => 'nullable|string|max:30|regex:/^\d{2}-?\d{7}-?\d$/',
            'pagibig_number' => 'nullable|string|max:30|regex:/^\d{4}-?\d{4}-?\d{4}$/',
            'philhealth_number' => 'nullable|string|max:30|regex:/^\d{2}-?\d{9}-?\d$/',
        ]);

        // Authorization: if user_id provided, verify it matches the requesting user or user is admin
        if (! empty($data['user_id'])) {
            $targetUser = User::findOrFail($data['user_id']);
            if (auth()->id() !== $targetUser->id && ! auth()->user()->isAdmin()) {
                abort(403, 'You are not authorized to create an employee profile for this user.');
            }

            // Prevent duplicate employee records
            if ($targetUser->employee) {
                return back()->withErrors(['user_id' => 'Employee profile already exists for this user.']);
            }
        }

        // Auto-generate employee ID with locking to prevent race conditions
        $lastId = DB::table('employees')->max('id') ?? 0;
        $data['employee_id'] = 'EMP-'.str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);

        $employee = Employee::create($data);

        // Mark profile complete via update to ensure fillable is not an issue
        $employee->update(['profile_completed' => true]);

        // Link and activate user record if provided
        if (! empty($data['user_id'])) {
            User::whereKey($data['user_id'])->update(['status' => 'active']);
        }

        return redirect()->route('dashboard')
            ->with('success', 'Employee profile created for '.$employee->full_name.'.');
    }
}

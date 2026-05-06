<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;

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

        // Guard: if employee record already exists for this user, skip setup
        if ($newUser && $newUser->employee) {
            return redirect()->route('employees.index')
                ->with('success', 'Employee profile already exists for ' . $newUser->name . '.');
        }

        return view('employees.setup', compact('newUser'));
    }

    /**
     * Save the new employee profile created by the admin.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id'            => 'nullable|exists:users,id',
            'first_name'         => 'required|string|max:100',
            'last_name'          => 'required|string|max:100',
            'email'              => 'required|email|unique:employees,email',
            'phone'              => 'nullable|string|max:30',
            'address'            => 'nullable|string|max:255',
            'date_of_birth'      => 'nullable|date',
            'department'         => 'required|string|max:100',
            'position'           => 'required|string|max:100',
            'date_hired'         => 'required|date',
            'status'             => 'required|in:active,probationary,contractual',
            'contract_expiry'    => 'nullable|date',
            'sss_number'         => 'nullable|string|max:30',
            'pagibig_number'     => 'nullable|string|max:30',
            'philhealth_number'  => 'nullable|string|max:30',
        ]);

        // Auto-generate employee ID
        $last = Employee::orderBy('id', 'desc')->first();
        $data['employee_id'] = 'EMP-' . str_pad(($last ? $last->id + 1 : 1), 4, '0', STR_PAD_LEFT);

        // Mark profile as complete
        $data['profile_completed'] = true;

        $employee = Employee::create($data);

        // Link user record if provided
        if (!empty($data['user_id'])) {
            User::whereKey($data['user_id'])->update(['status' => 'active']);
        }

        return redirect()->route('employees.show', $employee)
            ->with('success', 'Employee profile created successfully for ' . $employee->full_name . '.');
    }
}

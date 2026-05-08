<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with('user')->latest();

        if ($s = $request->search) {
            $query->where(function ($q) use ($s) {
                $q->where('employee_id', 'like', "%$s%")
                    ->orWhere('first_name', 'like', "%$s%")
                    ->orWhere('last_name', 'like', "%$s%");
            });
        }
        if ($d = $request->department) {
            $query->where('department', $d);
        }
        if ($p = $request->position) {
            $query->where('position', $p);
        }
        if ($st = $request->status) {
            $query->where('status', $st);
        }

        $employees = $query->paginate(20)->appends($request->all());
        $departments = Employee::distinct()->pluck('department')->sort();
        $positions = Employee::distinct()->pluck('position')->sort();

        return view('employees.index', compact('employees', 'departments', 'positions'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:employees',
            'department' => 'required|string|max:100',
            'position' => 'required|string|max:100',
            'date_hired' => 'required|date',
            'date_of_birth' => 'nullable|date',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'status' => 'required|in:active,probationary,contractual',
            'contract_expiry' => 'nullable|date',
            'sss_number' => 'nullable|string|max:30',
            'pagibig_number' => 'nullable|string|max:30',
            'philhealth_number' => 'nullable|string|max:30',
        ]);

        // Auto-generate employee ID
        $last = Employee::orderBy('id', 'desc')->first();
        $data['employee_id'] = 'EMP-'.str_pad(($last ? $last->id + 1 : 1), 4, '0', STR_PAD_LEFT);

        Employee::create($data);

        return redirect()->route('employees.index')
            ->with('success', 'Employee created successfully.');
    }

    public function show(Employee $employee)
    {
        $employee->load(['attendances', 'leaves', 'violations', 'performances', 'timesheets']);

        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:employees,email,'.$employee->id,
            'department' => 'required|string|max:100',
            'position' => 'required|string|max:100',
            'date_hired' => 'required|date',
            'date_of_birth' => 'nullable|date',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'status' => 'required|in:active,probationary,contractual',
            'contract_expiry' => 'nullable|date',
            'sss_number' => 'nullable|string|max:30',
            'pagibig_number' => 'nullable|string|max:30',
            'philhealth_number' => 'nullable|string|max:30',
        ]);

        $employee->update($data);

        return redirect()->route('employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    public function deactivate(Employee $employee)
    {
        $employee->update(['status' => 'inactive']);

        return redirect()->route('employees.index')
            ->with('success', 'Employee deactivated.');
    }

    public function activate(Employee $employee)
    {
        $employee->update(['status' => 'active']);

        return redirect()->route('employees.index')
            ->with('success', 'Employee reactivated.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();

        return redirect()->route('employees.index')
            ->with('success', 'Employee permanently deleted.');
    }
}

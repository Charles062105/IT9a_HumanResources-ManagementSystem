<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Shift;
use App\Services\AuditService;
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
                    ->orWhere('last_name', 'like', "%$s%")
                    ->orWhere('email', 'like', "%$s%")
                    ->orWhere('phone', 'like', "%$s%");
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
        $shifts = Shift::orderBy('name')->get();
        $departments = Employee::distinct()->pluck('department')->sort();
        $positions = Employee::distinct()->pluck('position')->sort();

        return view('employees.create', compact('shifts', 'departments', 'positions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:employees|unique:users',
            'department' => 'required|string|max:100',
            'position' => 'required|string|max:100',
            'date_hired' => 'required|date|before_or_equal:today',
            'date_of_birth' => 'nullable|date|before:today',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'status' => 'required|in:active,probationary,contractual,inactive',
            'shift_id' => 'nullable|exists:shifts,id',
            'contract_expiry' => 'required_if:status,contractual|nullable|date|after:today|before:'.now()->addYears(5)->format('Y-m-d'),
            'sss_number' => 'nullable|string|max:30|regex:/^\d{2}-?\d{7}-?\d$/',
            'pagibig_number' => 'nullable|string|max:30|regex:/^\d{4}-?\d{4}-?\d{4}$/',
            'philhealth_number' => 'nullable|string|max:30|regex:/^\d{2}-?\d{9}-?\d$/',
        ]);

        // Auto-generate employee ID
        $last = Employee::orderBy('id', 'desc')->first();
        $data['employee_id'] = 'EMP-'.str_pad(($last ? $last->id + 1 : 1), 4, '0', STR_PAD_LEFT);

        $employee = Employee::create($data);

        // Log this action
        AuditService::logCreate($employee, "Created employee: {$employee->full_name}");

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
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Only admins can edit employees.');
        }

        $shifts = Shift::orderBy('name')->get();
        $departments = Employee::distinct()->pluck('department')->sort();
        $positions = Employee::distinct()->pluck('position')->sort();
        $roles = ['employee', 'sub_admin', 'super_admin'];

        return view('employees.edit', compact('employee', 'shifts', 'departments', 'positions', 'roles'));
    }

    public function update(Request $request, Employee $employee)
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Only admins can update employees.');
        }

        $oldValues = $employee->getOriginal();

        $data = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:employees,email,'.$employee->id.'|unique:users,email',
            'department' => 'required|string|max:100',
            'position' => 'required|string|max:100',
            'date_hired' => 'required|date|before_or_equal:today',
            'date_of_birth' => 'nullable|date|before:today',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'status' => 'required|in:active,probationary,contractual,inactive',
            'shift_id' => 'nullable|exists:shifts,id',
            'contract_expiry' => 'required_if:status,contractual|nullable|date|after:today|before:'.now()->addYears(5)->format('Y-m-d'),
            'sss_number' => 'nullable|string|max:30|regex:/^\d{2}-?\d{7}-?\d$/',
            'pagibig_number' => 'nullable|string|max:30|regex:/^\d{4}-?\d{4}-?\d{4}$/',
            'philhealth_number' => 'nullable|string|max:30|regex:/^\d{2}-?\d{9}-?\d$/',
        ]);

        $employee->update($data);

        // Log this action
        AuditService::logUpdate($employee, $oldValues, "Updated employee: {$employee->full_name}");

        return redirect()->route('employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    public function deactivate(Employee $employee)
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Only admins can deactivate employees.');
        }

        $employee->update(['status' => 'inactive']);

        return redirect()->route('employees.index')
            ->with('success', 'Employee deactivated.');
    }

    public function activate(Employee $employee)
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Only admins can activate employees.');
        }

        $employee->update(['status' => 'active']);

        return redirect()->route('employees.index')
            ->with('success', 'Employee reactivated.');
    }

    public function updateRole(Request $request, Employee $employee)
    {
        if (! auth()->user()->isSuperAdmin()) {
            abort(403, 'Only super admins can manage roles.');
        }

        $validated = $request->validate([
            'role' => 'required|in:employee,sub_admin,super_admin',
        ]);

        // Create or update user if doesn't exist
        if (! $employee->user) {
            $employee->user()->create([
                'name' => $employee->full_name,
                'email' => $employee->email,
                'password' => bcrypt('default-password-change-required'),
                'role' => $validated['role'],
                'status' => 'active',
            ]);
        } else {
            $employee->user->update(['role' => $validated['role']]);
        }

        AuditService::logUpdate($employee, ['role' => $employee->user->role], "Role updated to {$validated['role']}");

        return redirect()->back()->with('success', "Role updated to {$validated['role']}.");
    }

    public function destroy(Employee $employee)
    {
        // Uses soft delete via SoftDeletes trait
        // Related records (attendances, leaves, violations, performances, timesheets) are preserved
        // Use forceDelete() only if hard deletion is explicitly required
        $employee->delete();

        return redirect()->route('employees.index')
            ->with('success', 'Employee deactivated and soft-deleted.');
    }

    public function batchDeactivate(Request $request)
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Only admins can deactivate employees.');
        }

        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return redirect()->route('employees.index')
                ->with('error', 'No employees selected.');
        }

        $employees = Employee::whereIn('id', $ids)->where('status', '!=', 'inactive')->get();

        foreach ($employees as $emp) {
            $emp->update(['status' => 'inactive']);
            // Log each deactivation
            AuditService::logDeactivate($emp, "Batch deactivation: {$emp->full_name}");
        }

        return redirect()->route('employees.index')
            ->with('success', "Deactivated {$employees->count()} employee(s).");
    }

    public function batchExport(Request $request)
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Only admins can export employee data.');
        }

        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return redirect()->route('employees.index')
                ->with('error', 'No employees selected.');
        }

        $employees = Employee::with('user')
            ->whereIn('id', $ids)
            ->get();

        // Generate CSV content
        $filename = 'employees_'.now()->format('Y-m-d_His').'.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($employees) {
            $file = fopen('php://output', 'w');

            // CSV Header
            fputcsv($file, [
                'Employee ID',
                'First Name',
                'Last Name',
                'Email',
                'Department',
                'Position',
                'Status',
                'Date Hired',
                'Contract Expiry',
                'Phone',
                'Date of Birth',
                'SSS',
                'Pag-IBIG',
                'PhilHealth',
                'Role',
            ]);

            // CSV Data
            foreach ($employees as $emp) {
                fputcsv($file, [
                    $emp->employee_id,
                    $emp->first_name,
                    $emp->last_name,
                    $emp->email,
                    $emp->department,
                    $emp->position,
                    $emp->status,
                    $emp->date_hired?->format('Y-m-d') ?? '',
                    $emp->contract_expiry?->format('Y-m-d') ?? '',
                    $emp->phone,
                    $emp->date_of_birth?->format('Y-m-d') ?? '',
                    $emp->sss_number,
                    $emp->pagibig_number,
                    $emp->philhealth_number,
                    $emp->user?->role ?? '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Violation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ViolationController extends Controller
{
    public function index(Request $request)
    {
        $query = Violation::with('employee')->latest();

        // Employees can only see their own violations
        if (Auth::user()->isEmployee()) {
            $employee = Auth::user()->employee;
            if ($employee) {
                $query->where('employee_id', $employee->id);
            } else {
                $query->whereRaw('1=0'); // No results if no employee record
            }
        }

        if ($s = $request->search) {
            $query->whereHas('employee', fn ($q) => $q->where('first_name', 'like', "%$s%")->orWhere('last_name', 'like', "%$s%"));
        }
        if ($l = $request->level) {
            $query->where('level', $l);
        }
        if ($dp = $request->department) {
            $query->whereHas('employee', fn ($q) => $q->where('department', $dp));
        }
        if ($st = $request->status) {
            $query->where('status', $st);
        }

        $violations = $query->paginate(20)->appends($request->all());
        $departments = Employee::distinct()->pluck('department')->sort();

        return view('violations.index', compact('violations', 'departments'));
    }

    public function my()
    {
        $employee = Auth::user()->employee;
        $violations = $employee
            ? Violation::where('employee_id', $employee->id)->latest()->paginate(15)
            : collect();

        return view('violations.my', compact('violations'));
    }

    public function create()
    {
        // Employees should only be able to log violations for themselves.
        if (Auth::user()->isEmployee()) {
            $employee = Auth::user()->employee;
            if (! $employee) {
                abort(403);
            }

            return view('violations.create', ['employees' => collect([$employee])]);
        }

        $employees = Employee::where('status', 'active')->orderBy('first_name')->get();

        return view('violations.create', compact('employees'));
    }

    public function store(Request $request)
    {
        // If the user is an employee, force employee_id to their own record.
        if (Auth::user()->isEmployee()) {
            $employee = Auth::user()->employee;
            if (! $employee) {
                abort(403);
            }

            $data = $request->validate([
                'level' => 'required|string',
                'offense' => 'required|string|max:255',
                'description' => 'nullable|string',
                'date' => 'required|date',
            ]);

            $data['employee_id'] = $employee->id;
        } else {
            $data = $request->validate([
                'employee_id' => 'required|exists:employees,id',
                'level' => 'required|string',
                'offense' => 'required|string|max:255',
                'description' => 'nullable|string',
                'date' => 'required|date',
            ]);
        }

        $data['status'] = 'open';
        $data['issued_by'] = Auth::id();

        DB::transaction(function () use ($data) {
            // Lock existing rows for this employee to serialize concurrent inserts.
            $data['offense_count'] = Violation::where('employee_id', $data['employee_id'])
                ->lockForUpdate()
                ->count() + 1;

            Violation::create($data);
        });

        return redirect()->route('violations.index')
            ->with('success', 'Violation recorded.');
    }

    public function show(Violation $violation)
    {
        $user = Auth::user();
        if ($user->isEmployee() && $user->employee?->id !== $violation->employee_id) {
            abort(403, 'Unauthorized.');
        }

        return view('violations.show', compact('violation'));
    }

    public function resolve(Violation $violation)
    {
        if (! Auth::user()->isAdmin()) {
            abort(403, 'Only admins can resolve violations.');
        }

        $violation->update(['status' => 'resolved']);

        return back()->with('success', 'Violation marked as resolved.');
    }
}

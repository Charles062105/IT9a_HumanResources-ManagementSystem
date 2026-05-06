<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\HrmsNotification;
use App\Models\Leave;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $query = Leave::with('employee')->latest();

        // Employees only see their own leaves
        if (auth()->check() && auth()->user()->isEmployee()) {
            $employee = auth()->user()->employee;
            if ($employee) {
                $query->where('employee_id', $employee->id);
            } else {
                $query->whereRaw('1=0');
            }
        }

        if ($s = $request->search) {
            $query->whereHas('employee', fn ($q) => $q
                ->where('first_name', 'like', "%$s%")
                ->orWhere('last_name', 'like', "%$s%"));
        }
        if ($t = $request->type) {
            $query->where('type', $t);
        }
        if ($st = $request->status) {
            $query->where('status', $st);
        }
        if ($m = $request->month) {
            $query->whereMonth('start_date', date('m', strtotime($m)));
        }

        $leaves = $query->paginate(20)->appends($request->all());

        return view('leaves.index', compact('leaves'));
    }

    public function create()
    {
        // Matrix: Employees submit & view own only; Admins approve/reject.
        // We allow both roles to open the creation page, but submit is enforced in store().
        $user = auth()->user();


        if ($user && $user->isEmployee()) {
            $employee = $user->employee;
            $employees = $employee ? Employee::where('id', $employee->id)->get() : collect();
        } else {
            $employees = Employee::where('status', 'active')->orderBy('first_name')->get();
        }


        return view('leaves.create', compact('employees'));
    }

    public function store(Request $request)
    {
        if (!auth()->check() || !auth()->user()->isAdmin() && !auth()->user()->isEmployee()) {
            abort(403, 'Unauthorized.');
        }


        $data = $request->validate([
            // Some forms might use different fields; keep broad validation.
            'employee_id' => 'nullable|exists:employees,id',
            'type'         => 'required|string',
            'reason'       => 'nullable|string',
            'start_date'   => 'required|date',
            'end_date'     => 'required|date|after_or_equal:start_date',
        ]);

        if (auth()->user()->isEmployee()) {
            $employee = auth()->user()->employee;
            if (!$employee) {
                abort(403, 'No employee record found.');
            }
            $data['employee_id'] = $employee->id;
        } else {
            // Admin submissions (if supported by your UI)
            $data['employee_id'] = $data['employee_id'] ?? auth()->user()->employee?->id;
            if (!$data['employee_id']) {
                abort(422, 'employee_id is required for admin submissions.');
            }
        }

        $data['status'] = 'pending';
        $data['requested_by'] = auth()->id();

        Leave::create($data);

        return back()->with('success', 'Leave request submitted.');
    }

    public function show(Leave $leave)
    {
        if (auth()->check() && auth()->user()->isEmployee()) {
            $employee = auth()->user()->employee;
            if (!$employee || (int) $leave->employee_id !== (int) $employee->id) {
                abort(403, 'Unauthorized.');
            }
        }

        return view('leaves.show', compact('leave'));
    }

    // Admin-only
    public function approve(Leave $leave)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized.');
        }

        $leave->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        HrmsNotification::create([
            'title' => 'Leave Approved',
            'message' => "Your {$leave->type} leave ({$leave->start_date->format('M j')} – {$leave->end_date->format('M j')}) has been approved.",
            'type' => 'success',
            'user_id' => optional($leave->employee)->user_id,
        ]);

        return back()->with('success', 'Leave approved.');
    }

    // Admin-only
    public function deny(Leave $leave)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized.');
        }

        $leave->update([
            'status' => 'denied',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Leave denied.');
    }
}


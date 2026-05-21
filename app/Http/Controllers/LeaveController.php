<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\HrmsNotification;
use App\Models\Leave;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $query = Leave::with('employee.user')->latest();

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
                ->where(function ($inner) use ($s) {
                    $inner->where('first_name', 'like', "%$s%")
                        ->orWhere('last_name', 'like', "%$s%");
                }));
        }
        if ($t = $request->type) {
            $query->where('type', $t);
        }
        if ($st = $request->status) {
            $query->where('status', $st);
        }
        if ($m = $request->month) {
            [$year, $month] = explode('-', $m);
            $query->whereYear('start_date', $year)->whereMonth('start_date', $month);
        }

        $leaves = $query->paginate(20)->appends($request->all());

        // KPI summary for admins — counts based on current filter scope
        $kpis = [];
        if (auth()->user()->isAdmin()) {
            $pendingCount = Leave::where('status', 'pending')->count();
            $approvedCount = Leave::where('status', 'approved')->count();
            $deniedCount = Leave::where('status', 'denied')->count();
            $totalCount = Leave::count();
            $kpis = [
                'pending' => $pendingCount,
                'approved' => $approvedCount,
                'denied' => $deniedCount,
                'total' => $totalCount,
            ];
        }

        return view('leaves.index', compact('leaves', 'kpis'));
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
        if (! auth()->check() || (! auth()->user()->isAdmin() && ! auth()->user()->isEmployee())) {
            abort(403, 'Unauthorized.');
        }

        $data = $request->validate([
            'employee_id' => 'nullable|exists:employees,id',
            'type' => 'required|in:vacation,sick,emergency,maternity,paternity,solo_parent',
            'reason' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        if (auth()->user()->isEmployee()) {
            $employee = auth()->user()->employee;
            if (! $employee) {
                abort(403, 'No employee record found.');
            }
            $data['employee_id'] = $employee->id;
        } else {
            // Admin submissions (if supported by your UI)
            $data['employee_id'] = $data['employee_id'] ?? auth()->user()->employee?->id;
            if (! $data['employee_id']) {
                abort(422, 'employee_id is required for admin submissions.');
            }
        }

        $data['status'] = 'pending';

        // Calculate number of days (inclusive of both start and end dates)
        $startDate = Carbon::parse($data['start_date']);
        $endDate = Carbon::parse($data['end_date']);
        $data['days'] = (int) $startDate->diffInDays($endDate) + 1;

        Leave::create($data);

        return back()->with('success', 'Leave request submitted.');
    }

    public function show($id)
    {
        $leave = Leave::with(['employee', 'approver'])->findOrFail($id);

        if (auth()->check() && auth()->user()->isEmployee()) {
            $employee = auth()->user()->employee;
            if (! $employee || (int) $leave->employee_id !== (int) $employee->id) {
                abort(403, 'Unauthorized.');
            }
        }

        return view('leaves.show', compact('leave'));
    }

    public function edit(Leave $leave)
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Only admins can edit leave requests.');
        }

        $leave->load(['employee.user', 'approver']);
        $employees = Employee::where('status', 'active')->orderBy('first_name')->get();

        return view('leaves.edit', compact('leave', 'employees'));
    }

    public function update(Request $request, Leave $leave)
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Only admins can update leave requests.');
        }

        $data = $request->validate([
            'type' => 'required|in:vacation,sick,emergency,maternity,paternity,solo_parent',
            'reason' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = Carbon::parse($data['start_date']);
        $endDate = Carbon::parse($data['end_date']);
        $data['days'] = (int) $startDate->diffInDays($endDate) + 1;

        $leave->update($data);

        return redirect()->route('leaves.index')->with('success', 'Leave request updated.');
    }

    // Admin-only
    public function approve(Leave $leave)
    {
        if (! auth()->check() || ! auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized.');
        }

        $leave->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        // Refresh so approved_at is current for the notes below
        $leave->refresh();

        // Auto-create ON_LEAVE attendance records for each day of leave
        $currentDate = Carbon::parse($leave->start_date);
        $endDate = Carbon::parse($leave->end_date);

        while ($currentDate <= $endDate) {
            $existing = Attendance::where('employee_id', $leave->employee_id)
                ->whereDate('date', $currentDate->toDateString())
                ->first();

            // Only create/update if no time was already recorded (preserve clock-in/out data)
            if (! $existing || ($existing && ! $existing->time_in && ! $existing->time_out)) {
                Attendance::updateOrCreate(
                    [
                        'employee_id' => $leave->employee_id,
                        'date' => $currentDate->toDateString(),
                    ],
                    [
                        'status' => 'on_leave',
                        'notes' => "On {$leave->type} leave (approved {$leave->approved_at?->format('M j, Y')})",
                    ]
                );
            }
            $currentDate->addDay();
        }

        HrmsNotification::create([
            'title' => 'Leave Approved',
            'message' => "Your {$leave->type} leave ({$leave->start_date?->format('M j')} – {$leave->end_date?->format('M j')}) has been approved.",
            'type' => 'success',
            'user_id' => optional($leave->employee)->user_id,
        ]);

        return back()->with('success', 'Leave approved.');
    }

    // Admin-only
    public function deny(Leave $leave)
    {
        if (! auth()->check() || ! auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized.');
        }

        $leave->update([
            'status' => 'denied',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        HrmsNotification::create([
            'title' => 'Leave Request Denied',
            'message' => "Your {$leave->type} leave request ({$leave->start_date?->format('M j')} – {$leave->end_date?->format('M j')}) has been denied.",
            'type' => 'error',
            'user_id' => optional($leave->employee)->user_id,
        ]);

        return back()->with('success', 'Leave denied.');
    }
}

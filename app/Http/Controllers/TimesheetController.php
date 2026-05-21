<?php

namespace App\Http\Controllers;

use App\Http\Requests\RejectTimesheetRequest;
use App\Http\Requests\StoreTimesheetRequest;
use App\Http\Requests\UpdateTimesheetRequest;
use App\Models\AssignedTimesheet;
use App\Models\Employee;
use App\Models\HrmsNotification;
use App\Models\Timesheet;
use Carbon\Carbon;

class TimesheetController extends Controller
{
    public function index()
    {
        $query = Timesheet::with(['employee', 'approver'])->latest();

        if (auth()->user()?->isEmployee()) {
            $employee = auth()->user()->employee;
            if ($employee) {
                $query->where('employee_id', $employee->id);
            } else {
                $query->whereRaw('1=0');
            }
        }

        if ($s = request('search')) {
            $query->whereHas('employee', fn ($q) => $q
                ->where(function ($inner) use ($s) {
                    $inner->where('first_name', 'like', "%$s%")
                        ->orWhere('last_name', 'like', "%$s%");
                }));
        }
        if ($dp = request('department')) {
            $query->whereHas('employee', fn ($q) => $q->where('department', $dp));
        }
        if ($w = request('week')) {
            $query->where('week_label', $w);
        }
        if ($st = request('status')) {
            $query->where('status', $st);
        }

        $records = $query->paginate(20)->appends(request()->all());
        $departments = Employee::distinct()->pluck('department')->sort();
        $weeks = Timesheet::distinct()->pluck('week_label')->sort()->reverse();

        return view('timesheets.index', compact('records', 'departments', 'weeks'));
    }

    public function my()
    {
        $employee = auth()->user()->employee;
        if (! $employee) {
            return redirect()->route('dashboard')->with('error', 'No employee record linked to your account.');
        }

        $query = Timesheet::where('employee_id', $employee->id)->latest();

        if ($st = request('status')) {
            $query->where('status', $st);
        }
        if ($w = request('week')) {
            $query->where('week_label', $w);
        }

        $records = $query->paginate(10)->appends(request()->all());
        $weeks = Timesheet::where('employee_id', $employee->id)
            ->distinct()->pluck('week_label')->sort()->reverse();

        return view('timesheets.my', compact('records', 'weeks'));
    }

    public function create()
    {
        $isAdmin = auth()->user()->isAdmin();
        $employees = $isAdmin ? Employee::where('status', 'active')->orderBy('first_name')->get() : null;

        $employee = auth()->user()->employee;
        $pendingTasks = $employee
            ? AssignedTimesheet::where('employee_id', $employee->id)
                ->whereIn('status', ['pending', 'in_progress'])
                ->orderBy('due_date')
                ->get()
            : collect();

        return view('timesheets.create', compact('isAdmin', 'employees', 'pendingTasks'));
    }

    public function store(StoreTimesheetRequest $request)
    {
        $isAdmin = auth()->user()->isAdmin();
        $data = $request->validated();

        if (! $isAdmin) {
            $employee = auth()->user()->employee;
            if (! $employee) {
                return back()->with('error', 'No employee record linked to your account.');
            }
            $data['employee_id'] = $employee->id;
        }

        $data['week_label'] = Carbon::parse($data['week_start'])->format('M d')
            .'–'.Carbon::parse($data['week_end'])->format('M d');
        $data['status'] = 'pending';
        $data['submitted_at'] = now();

        Timesheet::create($data);

        return redirect()->route('timesheets.index')
            ->with('success', 'Timesheet submitted.');
    }

    public function approve(Timesheet $timesheet)
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Only admins can approve timesheets.');
        }

        $timesheet->update(['status' => 'approved', 'approved_by' => auth()->id()]);

        if ($timesheet->assigned_timesheet_id) {
            $task = AssignedTimesheet::find($timesheet->assigned_timesheet_id);
            if ($task) {
                $task->update(['status' => 'approved', 'approved_by' => auth()->id()]);
            }
        }

        HrmsNotification::create([
            'title' => 'Timesheet Approved',
            'message' => "Your timesheet for {$timesheet->week_label} ({$timesheet->total_hours}h total) has been approved.",
            'type' => 'success',
            'user_id' => optional($timesheet->employee)->user_id,
        ]);

        return back()->with('success', 'Timesheet approved.');
    }

    public function reject(RejectTimesheetRequest $request, Timesheet $timesheet)
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Only admins can reject timesheets.');
        }

        $timesheet->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'rejection_reason' => $request->input('reason'),
        ]);

        if ($timesheet->assigned_timesheet_id) {
            $task = AssignedTimesheet::find($timesheet->assigned_timesheet_id);
            if ($task) {
                $task->update(['status' => 'rejected']);
            }
        }

        HrmsNotification::create([
            'title' => 'Timesheet Rejected',
            'message' => "Your timesheet for {$timesheet->week_label} has been rejected.".($request->filled('reason') ? " Reason: {$request->input('reason')}" : ' You may resubmit.'),
            'type' => 'error',
            'user_id' => optional($timesheet->employee)->user_id,
        ]);

        return back()->with('success', 'Timesheet rejected.');
    }

    public function show(Timesheet $timesheet)
    {
        $this->authorizeView($timesheet);

        return view('timesheets.show', compact('timesheet'));
    }

    public function edit(Timesheet $timesheet)
    {
        $this->authorizeView($timesheet);

        return view('timesheets.edit', compact('timesheet'));
    }

    public function update(UpdateTimesheetRequest $request, Timesheet $timesheet)
    {
        $this->authorizeView($timesheet);

        $data = $request->validated();

        if ($timesheet->status === 'rejected') {
            $timesheet->update(['status' => 'pending']);
        }

        $timesheet->update($data);

        return redirect()->route(
            auth()->user()->isAdmin() ? 'timesheets.index' : 'timesheets.my'
        )->with('success', 'Timesheet updated.');
    }

    private function authorizeView(Timesheet $timesheet): void
    {
        $user = auth()->user();
        if ($user->isAdmin()) {
            return;
        }
        if ($user->employee?->id === $timesheet->employee_id) {
            return;
        }
        abort(403, 'You are not authorized to view this timesheet.');
    }
}

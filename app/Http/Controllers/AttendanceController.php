<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Services\AttendanceService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function __construct(private AttendanceService $attendanceService) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Attendance::class);

        $query = Attendance::with('employee.shift')->latest('date');

        if (auth()->user()->isEmployee()) {
            $employee = auth()->user()->employee;
            if ($employee) {
                $query->where('employee_id', $employee->id);
            } else {
                $query->whereRaw('1=0');
            }
        }

        if ($s = $request->search) {
            $query->whereHas('employee', fn ($q) => $q->where('first_name', 'like', "%$s%")->orWhere('last_name', 'like', "%$s%"));
        }

        // Date range filter
        if ($from = $request->date_from) {
            $query->whereDate('date', '>=', $from);
        }
        if ($to = $request->date_to) {
            $query->whereDate('date', '<=', $to);
        }

        if ($dp = $request->department) {
            $query->whereHas('employee', fn ($q) => $q->where('department', $dp));
        }
        if ($st = $request->status) {
            $query->where('status', $st);
        }

        $records = $query->paginate(20)->appends($request->all());
        $departments = Employee::distinct()->pluck('department')->sort();

        $todayRecord = auth()->user()->employee
            ? Attendance::where('employee_id', auth()->user()->employee->id)
                ->whereDate('date', today())
                ->first()
            : null;

        // KPI summary for admins — count today's breakdown
        $kpis = [];
        if (auth()->user()->isAdmin()) {
            $kpis = [
                'present' => Attendance::whereDate('date', today())->where('status', 'present')->count(),
                'late' => Attendance::whereDate('date', today())->where('status', 'late')->count(),
                'absent' => Attendance::whereDate('date', today())->where('status', 'absent')->count(),
                'on_leave' => Attendance::whereDate('date', today())->where('status', 'on_leave')->count(),
                'total' => Attendance::whereDate('date', today())->count(),
            ];
        }

        return view('attendance.index', compact('records', 'departments', 'todayRecord', 'kpis'));
    }

    public function timeIn(Request $request)
    {
        $employee = auth()->user()->employee;
        if (! $employee) {
            abort(403, 'Only employees can record time.');
        }

        $this->authorize('create', Attendance::class);

        try {
            $manualTime = $request->filled('time')
                ? Carbon::parse($request->time)
                : null;

            $record = $this->attendanceService->recordTimeIn($employee, $manualTime);

            return back()->with('success', 'Time in recorded: '.$record->time_in->format('h:i A'));
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function timeOut(Request $request)
    {
        $employee = auth()->user()->employee;
        if (! $employee) {
            abort(403, 'Only employees can record time.');
        }

        $this->authorize('create', Attendance::class);

        try {
            $manualTime = $request->filled('time')
                ? Carbon::parse($request->time)
                : null;

            $record = $this->attendanceService->recordTimeOut($employee, $manualTime);
            $hoursWorked = $this->attendanceService->getHoursWorked($record);
            $message = 'Time out recorded';
            if ($hoursWorked) {
                $whole = floor($hoursWorked);
                $mins = round(($hoursWorked - $whole) * 60);
                $message .= sprintf(' — %dh %02dm worked', $whole, $mins);
            }

            return back()->with('success', $message);
        } catch (ModelNotFoundException) {
            return back()->with('error', 'No active time-in found.');
        }
    }

    public function edit(Attendance $attendance)
    {
        $this->authorize('update', $attendance);

        $employees = Employee::where('status', 'active')->orderBy('first_name')->get();

        return view('attendance.edit', compact('attendance', 'employees'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        $this->authorize('update', $attendance);

        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'time_in' => 'nullable|date',
            'time_out' => 'nullable|date|after_or_equal:time_in',
            'status' => 'required|in:present,late,absent,half_day,on_leave',
            'notes' => 'nullable|string',
        ]);

        if (! empty($data['time_in'])) {
            $data['time_in'] = Carbon::parse($data['time_in']);
        }
        if (! empty($data['time_out'])) {
            $data['time_out'] = Carbon::parse($data['time_out']);
        }

        $attendance->update($data);

        return redirect()->route('attendance.index')
            ->with('success', 'Attendance record updated.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with('employee')->latest('date');

        // Employees can only see their own attendance
        if (auth()->user()->isEmployee()) {
            $employee = auth()->user()->employee;
            if ($employee) {
                $query->where('employee_id', $employee->id);
            } else {
                $query->whereRaw('1=0'); // No results if no employee record
            }
        }

        if ($s = $request->search) {
            $query->whereHas('employee', fn ($q) => $q->where('first_name', 'like', "%$s%")->orWhere('last_name', 'like', "%$s%"));
        }
        if ($d = $request->date) {
            $query->whereDate('date', $d);
        }
        if ($dp = $request->department) {
            $query->whereHas('employee', fn ($q) => $q->where('department', $dp));
        }
        if ($st = $request->status) {
            $query->where('status', $st);
        }

        $records = $query->paginate(25)->appends($request->all());
        $departments = Employee::distinct()->pluck('department')->sort();

        return view('attendance.index', compact('records', 'departments'));
    }

    public function timeIn(Request $request)
    {
        $employee = auth()->user()->employee;
        if (! $employee) {
            return back()->with('error', 'No employee record found.');
        }

        $today = Carbon::today();
        $existing = Attendance::where('employee_id', $employee->id)->whereDate('date', $today)->first();
        if ($existing) {
            return back()->with('error', 'Already timed in today.');
        }

        $timeIn = Carbon::now();
        $cutoff = Carbon::today()->setTime(8, 30);
        $status = $timeIn->gt($cutoff) ? 'late' : 'present';

        Attendance::create([
            'employee_id' => $employee->id,
            'date' => $today,
            'time_in' => $timeIn,
            'status' => $status,
        ]);

        return back()->with('success', 'Time in recorded: '.$timeIn->format('h:i A'));
    }

    public function timeOut(Request $request)
    {
        $employee = auth()->user()->employee;
        if (! $employee) {
            return back()->with('error', 'No employee record found.');
        }

        $record = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', Carbon::today())
            ->whereNull('time_out')->first();

        if (! $record) {
            return back()->with('error', 'No active time-in found.');
        }

        $record->update(['time_out' => Carbon::now()]);

        return back()->with('success', 'Time out recorded.');
    }

    public function markAbsent(Request $request, Attendance $attendance)
    {
        $attendance->update(['status' => 'absent']);

        return back()->with('success', 'Marked as absent.');
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();

        return back()->with('success', 'Record deleted.');
    }
}

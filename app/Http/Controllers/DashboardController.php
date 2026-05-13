<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\UserRequest;
use App\Models\Violation;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $user = auth()->user();

        // Return employee dashboard if user is an employee
        if ($user->isEmployee()) {
            return $this->employeeDashboard($user, $today);
        }

        // Admin dashboard
        return $this->adminDashboard($today);
    }

    private function adminDashboard($today)
    {
        // KPI stats
        $totalEmployees = Employee::where('status', 'active')->count();
        $presentToday = Attendance::whereDate('date', $today)->where('status', 'present')->count();
        $lateToday = Attendance::whereDate('date', $today)->where('status', 'late')->count();
        $attendanceRate = $totalEmployees > 0
            ? round(($presentToday + $lateToday) / $totalEmployees * 100, 1)
            : 0;
        $pendingLeaves = Leave::where('status', 'pending')->count();
        $openViolations = Violation::where('status', 'open')->count();

        // 7-day attendance chart data
        $chartDays = collect();
        $chartPresent = collect();
        $chartAbsent = collect();
        for ($i = 6; $i >= 0; $i--) {
            $day = Carbon::today()->subDays($i);
            $chartDays->push($day->format('D j'));
            $present = Attendance::whereDate('date', $day)
                ->whereIn('status', ['present', 'late'])->count();
            $absent = Attendance::whereDate('date', $day)
                ->where('status', 'absent')->count();
            $chartPresent->push($present);
            $chartAbsent->push($absent);
        }

        // Quick lists
        $pendingLeaveList = Leave::with('employee')
            ->where('status', 'pending')
            ->latest()->limit(3)->get();

        $recentViolations = Violation::with('employee')
            ->where('status', 'open')
            ->latest()->limit(3)->get();

        // Milestones (birthdays & anniversaries today)
        $birthdays = Employee::whereMonth('date_of_birth', $today->month)
            ->whereDay('date_of_birth', $today->day)
            ->limit(2)->get();

        $anniversaries = Employee::whereMonth('date_hired', $today->month)
            ->whereDay('date_hired', $today->day)
            ->where('date_hired', '!=', $today)
            ->limit(2)->get();

        $milestones = $birthdays->map(fn ($e) => ['employee' => $e, 'type' => 'birthday'])
            ->merge($anniversaries->map(fn ($e) => [
                'employee' => $e,
                'type' => 'anniversary',
                'years' => $today->year - Carbon::parse($e->date_hired)->year,
            ]));

        // Task chips for welcome strip
        $pendingRequests = UserRequest::where('status', 'pending')->count();

        // Today's attendance for current user
        $currentEmployee = auth()->user()->employee;
        $todayAttendance = $currentEmployee
            ? Attendance::where('employee_id', $currentEmployee->id)
                ->whereDate('date', $today)
                ->first()
            : null;

        return view('dashboard.index', compact(
            'totalEmployees', 'attendanceRate', 'presentToday',
            'pendingLeaves', 'openViolations',
            'chartDays', 'chartPresent', 'chartAbsent',
            'pendingLeaveList', 'recentViolations',
            'milestones', 'pendingRequests', 'todayAttendance'
        ));
    }

    private function employeeDashboard($user, $today)
    {
        $currentEmployee = $user->employee;

        // If no employee record, show basic view with default stats
        if (! $currentEmployee) {
            return view('dashboard.employee', [
                'todayAttendance' => null,
                'currentEmployee' => null,
                'myLeaves' => collect(),
                'myViolations' => collect(),
                'presentDays' => 0,
                'absentDays' => 0,
                'totalWorkDays' => 0,
                'chartDays' => collect(),
                'chartPresent' => collect(),
                'chartAbsent' => collect(),
            ]);
        }

        // Today's attendance
        $todayAttendance = Attendance::where('employee_id', $currentEmployee->id)
            ->whereDate('date', $today)
            ->first();

        // My leaves (last 5)
        $myLeaves = Leave::where('employee_id', $currentEmployee->id)
            ->latest()
            ->limit(5)
            ->get();

        // My violations (last 5)
        $myViolations = Violation::where('employee_id', $currentEmployee->id)
            ->latest()
            ->limit(5)
            ->get();

        // My attendance stats (last 30 days)
        $thirtyDaysAgo = $today->copy()->subDays(30);
        $myAttendanceStats = Attendance::where('employee_id', $currentEmployee->id)
            ->whereBetween('date', [$thirtyDaysAgo, $today])
            ->get();

        $presentDays = $myAttendanceStats->whereIn('status', ['present', 'late'])->count();
        $absentDays = $myAttendanceStats->where('status', 'absent')->count();
        $totalWorkDays = $myAttendanceStats->count();

        // 7-day personal attendance chart
        $sevenDaysAgo = $today->copy()->subDays(6);
        $weekAttendance = Attendance::where('employee_id', $currentEmployee->id)
            ->whereBetween('date', [$sevenDaysAgo, $today])
            ->get()
            ->keyBy(fn ($a) => Carbon::parse($a->date)->toDateString());

        $chartDays = collect();
        $chartPresent = collect();
        $chartAbsent = collect();
        for ($i = 6; $i >= 0; $i--) {
            $day = $today->copy()->subDays($i);
            $chartDays->push($day->format('D j'));
            $attendance = $weekAttendance->get($day->toDateString());

            if ($attendance) {
                $isPresent = in_array($attendance->status, ['present', 'late']) ? 1 : 0;
                $isAbsent = $attendance->status === 'absent' ? 1 : 0;
            } else {
                $isPresent = 0;
                $isAbsent = 0;
            }

            $chartPresent->push($isPresent);
            $chartAbsent->push($isAbsent);
        }

        return view('dashboard.employee', compact(
            'todayAttendance',
            'currentEmployee',
            'myLeaves',
            'myViolations',
            'presentDays',
            'absentDays',
            'totalWorkDays',
            'chartDays',
            'chartPresent',
            'chartAbsent'
        ));
    }
}

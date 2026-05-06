<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\Violation;
use App\Models\Performance;
use App\Models\Timesheet;
use App\Models\HrmsNotification;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // KPI stats
        $totalEmployees  = Employee::where('status', 'active')->count();
        $presentToday    = Attendance::whereDate('date', $today)->where('status', 'present')->count();
        $lateToday       = Attendance::whereDate('date', $today)->where('status', 'late')->count();
        $attendanceRate  = $totalEmployees > 0
            ? round(($presentToday + $lateToday) / $totalEmployees * 100, 1)
            : 0;
        $pendingLeaves   = Leave::where('status', 'pending')->count();
        $openViolations  = Violation::where('status', 'open')->count();

        // 7-day attendance chart data
        $chartDays    = collect();
        $chartPresent = collect();
        $chartAbsent  = collect();
        for ($i = 6; $i >= 0; $i--) {
            $day = Carbon::today()->subDays($i);
            $chartDays->push($day->format('D j'));
            $present = Attendance::whereDate('date', $day)
                ->whereIn('status', ['present','late'])->count();
            $absent  = Attendance::whereDate('date', $day)
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

        $milestones = $birthdays->map(fn($e) => ['employee' => $e, 'type' => 'birthday'])
            ->merge($anniversaries->map(fn($e) => [
                'employee' => $e,
                'type'     => 'anniversary',
                'years'    => $today->year - Carbon::parse($e->date_hired)->year,
            ]));

        // Task chips for welcome strip
        $pendingRequests = \App\Models\UserRequest::where('status', 'pending')->count();

        return view('dashboard.index', compact(
            'totalEmployees', 'attendanceRate', 'presentToday',
            'pendingLeaves', 'openViolations',
            'chartDays', 'chartPresent', 'chartAbsent',
            'pendingLeaveList', 'recentViolations',
            'milestones', 'pendingRequests'
        ));
    }
}

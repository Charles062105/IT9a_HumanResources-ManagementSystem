<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Database\QueryException;

class AttendanceService
{
    /**
     * Record time-in for an employee
     */
    public function recordTimeIn(Employee $employee, ?Carbon $manualTime = null): Attendance
    {
        $today = Carbon::today();

        $timeIn = $manualTime ?? Carbon::now();
        $status = $this->determineStatus($employee, $timeIn);

        try {
            return Attendance::create([
                'employee_id' => $employee->id,
                'date' => $today,
                'time_in' => $timeIn,
                'status' => $status,
            ]);
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') { // Unique constraint violation
                throw new \DomainException('Employee already timed in today');
            }
            throw $e;
        }
    }

    /**
     * Record time-out for an employee
     */
    public function recordTimeOut(Employee $employee, ?Carbon $manualTime = null): Attendance
    {
        $record = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', Carbon::today())
            ->whereNull('time_out')
            ->firstOrFail();

        $timeOut = $manualTime ?? Carbon::now();
        $record->update(['time_out' => $timeOut]);

        // Check if overtime and update status if needed
        if ($this->isOvertime($employee, $record)) {
            $overtimeNote = 'Overtime: '.$this->getOvertimeMinutes($employee, $record).' minutes';
            if (! str_contains($record->notes ?? '', 'Overtime:')) {
                $record->update(['notes' => ($record->notes ? $record->notes.' | ' : '').$overtimeNote]);
            }
        }

        return $record->fresh();
    }

    /**
     * Determine attendance status based on shift and time-in
     */
    public function determineStatus(Employee $employee, Carbon $timeIn): string
    {
        $shift = $employee->shift;
        if (! $shift) {
            // Default behavior if no shift assigned
            return $timeIn->format('H:i') > '08:30' ? 'late' : 'present';
        }

        // Parse shift start time
        $shiftStart = Carbon::createFromFormat('H:i:s', $shift->start_time->format('H:i:s'));
        $shiftStartMinutesToday = $shiftStart->hour * 60 + $shiftStart->minute;

        // Get time-in time of day in minutes
        $timeInMinutesToday = $timeIn->hour * 60 + $timeIn->minute;

        // Grace period cutoff
        $gracePeriodMinutes = $shift->grace_period_minutes ?? 15;
        $cutoffMinutes = $shiftStartMinutesToday + $gracePeriodMinutes;

        if ($timeInMinutesToday <= $cutoffMinutes) {
            return 'present';
        } else {
            return 'late';
        }
    }

    /**
     * Check if employee worked overtime
     */
    public function isOvertime(Employee $employee, Attendance $attendance): bool
    {
        if (! $attendance->time_in || ! $attendance->time_out) {
            return false;
        }

        $overtimeMinutes = $this->getOvertimeMinutes($employee, $attendance);

        return $overtimeMinutes > 0;
    }

    /**
     * Get overtime minutes for an attendance record
     */
    public function getOvertimeMinutes(Employee $employee, Attendance $attendance): int
    {
        if (! $attendance->time_in || ! $attendance->time_out) {
            return 0;
        }

        $shift = $employee->shift;
        if (! $shift) {
            return 0;
        }

        // Parse shift end time
        $shiftEnd = Carbon::createFromFormat('H:i:s', $shift->end_time->format('H:i:s'));
        $shiftEndMinutesToday = $shiftEnd->hour * 60 + $shiftEnd->minute;

        // Parse shift start time for night shift detection
        $shiftStart = Carbon::createFromFormat('H:i:s', $shift->start_time->format('H:i:s'));
        $shiftStartMinutesToday = $shiftStart->hour * 60 + $shiftStart->minute;

        // Get time-out time of day in minutes
        $timeOutMinutesToday = $attendance->time_out->hour * 60 + $attendance->time_out->minute;

        // Handle shift crossing midnight (night shift)
        if ($shiftEndMinutesToday < $shiftStartMinutesToday) {
            if ($timeOutMinutesToday < 6 * 60) { // If time-out is early morning, shift ended after midnight
                $timeOutMinutesToday += 24 * 60; // Add 24 hours
            }
            $shiftEndMinutesToday += 24 * 60; // Shift end is next day
        }

        $minutesAfterShift = max(0, $timeOutMinutesToday - $shiftEndMinutesToday);
        $overtimeThreshold = $shift->overtime_threshold_minutes ?? 0;

        return max(0, $minutesAfterShift - $overtimeThreshold);
    }

    /**
     * Get hours worked for an attendance record
     */
    public function getHoursWorked(Attendance $attendance): ?float
    {
        if (! $attendance->time_in || ! $attendance->time_out) {
            return null;
        }

        return round($attendance->time_in->diffInMinutes($attendance->time_out) / 60, 2);
    }

    /**
     * Mark employee as absent if they didn't time in
     */
    public function markAbsentIfNoTimeIn(Employee $employee, ?Carbon $date = null): void
    {
        $date = $date ?? Carbon::today();

        $existing = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', $date)
            ->exists();

        if (! $existing) {
            Attendance::create([
                'employee_id' => $employee->id,
                'date' => $date,
                'status' => 'absent',
            ]);
        }
    }

    /**
     * Get attendance summary for a date range
     */
    public function getAttendanceSummary(Employee $employee, Carbon $startDate, Carbon $endDate): array
    {
        $records = Attendance::where('employee_id', $employee->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        $totalOvertimeMinutes = $records->sum(fn ($record) => $this->getOvertimeMinutes($employee, $record));

        return [
            'present' => $records->where('status', 'present')->count(),
            'late' => $records->where('status', 'late')->count(),
            'absent' => $records->where('status', 'absent')->count(),
            'half_day' => $records->where('status', 'half_day')->count(),
            'on_leave' => $records->where('status', 'on_leave')->count(),
            'total_hours_worked' => $records->sum(fn ($r) => $this->getHoursWorked($r) ?? 0),
            'total_overtime_minutes' => $totalOvertimeMinutes,
            'total_overtime_hours' => round($totalOvertimeMinutes / 60, 2),
        ];
    }

    /**
     * Get today's status for an employee
     */
    public function getTodayStatus(Employee $employee): ?array
    {
        $today = Carbon::today();
        $attendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', $today)
            ->first();

        if (! $attendance) {
            return null;
        }

        return [
            'date' => $attendance->date,
            'time_in' => $attendance->time_in,
            'time_out' => $attendance->time_out,
            'status' => $attendance->status,
            'hours_worked' => $this->getHoursWorked($attendance),
            'overtime_minutes' => $this->getOvertimeMinutes($employee, $attendance),
        ];
    }
}

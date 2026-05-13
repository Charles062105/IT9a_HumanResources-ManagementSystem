<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Console\Command;

class MarkAbsentEmployees extends Command
{
    protected $signature = 'app:mark-absent-employees';

    protected $description = 'Mark all active employees without attendance today as absent';

    public function handle(): int
    {
        $today = Carbon::today();

        $activeEmployees = Employee::where('status', 'active')->get();

        $marked = 0;
        $skipped = 0;

        foreach ($activeEmployees as $employee) {
            // Skip if already has an attendance record today (clocked in or on leave)
            $hasRecord = Attendance::where('employee_id', $employee->id)
                ->whereDate('date', $today)
                ->exists();

            if ($hasRecord) {
                $skipped++;

                continue;
            }

            Attendance::create([
                'employee_id' => $employee->id,
                'date' => $today,
                'status' => 'absent',
                'notes' => 'Auto-marked absent — no clock-in recorded',
            ]);

            $marked++;
        }

        $this->info("Marked {$marked} employee(s) as absent. ({$skipped} already had records.)");

        return Command::SUCCESS;
    }
}

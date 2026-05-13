<?php

use App\Models\Attendance;
use Carbon\Carbon;

$today = Carbon::today();
$attendancesForToday = Attendance::whereDate('date', $today)->get()->groupBy('employee_id');

foreach ($attendancesForToday as $employeeId => $records) {
    if ($records->count() > 1) {
        // Keep the last one (most recent), delete others
        $recordsToDelete = $records->sortBy('created_at')->reverse()->slice(1);
        $count = $recordsToDelete->count();
        foreach ($recordsToDelete as $record) {
            $record->delete();
        }
        echo "Cleaned up employee $employeeId: deleted $count duplicate(s)\n";
    }
}

echo "Cleanup complete!\n";

<?php

namespace App\Console\Commands;

use App\Models\Employee;
use App\Models\HrmsNotification;
use Illuminate\Console\Command;

class CheckContractExpiry extends Command
{
    protected $signature = 'contracts:check-expiry';

    protected $description = 'Check for contracts expiring within 30 days and create notifications';

    public function handle()
    {
        $thirtyDaysAhead = now()->addDays(30);
        $startOfToday = now()->startOfDay();

        // Find contracts expiring between today and 30 days from now
        $expiringContracts = Employee::with('user')
            ->where('contract_expiry', '>=', $startOfToday)
            ->where('contract_expiry', '<=', $thirtyDaysAhead)
            ->get();

        $count = 0;
        foreach ($expiringContracts as $employee) {
            // Check if notification already exists for this employee
            $existingNotification = HrmsNotification::where('user_id', $employee->user_id)
                ->where('type', 'contract_expiry')
                ->where('reference_id', $employee->id)
                ->where('read_at', null)
                ->exists();

            if (! $existingNotification && $employee->user_id) {
                // Calculate days remaining
                $daysRemaining = now()->diffInDays($employee->contract_expiry, false);

                // Create notification
                HrmsNotification::create([
                    'user_id' => $employee->user_id,
                    'type' => 'contract_expiry',
                    'message' => "Contract for {$employee->full_name} expires in {$daysRemaining} days ({$employee->contract_expiry->format('M j, Y')})",
                    'reference_id' => $employee->id,
                    'reference_type' => 'employee',
                    'read_at' => null,
                ]);

                $count++;
            }
        }

        $this->info("Created {$count} contract expiry notification(s).");
    }
}

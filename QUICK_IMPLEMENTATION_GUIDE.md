# Quick Implementation Guide - Critical Gaps

**Target:** Close critical gaps in 6-8 hours  
**Priority:** Implement in this order

---

## 1️⃣ IMPLEMENT SCHEDULER (30 minutes)

### Step 1: Create the scheduler configuration in `bootstrap/app.php`

```php
// Around line 16, after withMiddleware()
->withSchedule(function (\Illuminate\Console\Scheduling\Schedule $schedule): void {
    // Daily tasks
    $schedule->command('attendance:mark-absent')->dailyAt('09:00');
    $schedule->command('notifications:cleanup')->weekly();
})
```

### Step 2: Verify Laravel Pint formatting
```bash
vendor/bin/pint --dirty --format agent bootstrap/app.php
```

---

## 2️⃣ CREATE MARK-ABSENT COMMAND (1.5 hours)

### Step 1: Generate command
```bash
php artisan make:command MarkAbsentEmployees --no-interaction
```

### Step 2: Implement the command logic

**File:** `app/Console/Commands/MarkAbsentEmployees.php`

```php
<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Console\Command;

class MarkAbsentEmployees extends Command
{
    protected $signature = 'attendance:mark-absent';
    protected $description = 'Mark employees as absent if they have no attendance record for today';

    public function handle(): int
    {
        $today = Carbon::today();
        
        // Get all active employees
        $employees = Employee::all();
        
        foreach ($employees as $employee) {
            // Check if attendance record exists for today
            $hasAttendance = Attendance::where('employee_id', $employee->id)
                ->whereDate('date', $today)
                ->exists();
            
            // If no record, mark as absent
            if (!$hasAttendance) {
                Attendance::create([
                    'employee_id' => $employee->id,
                    'date' => $today,
                    'status' => 'absent',
                    'clock_in' => null,
                    'clock_out' => null,
                    'notes' => 'Auto-marked absent - no clock-in record',
                ]);
                
                $this->info("Marked {$employee->user->name} as absent");
            }
        }
        
        $this->info("Daily absence marking completed at " . now());
        return Command::SUCCESS;
    }
}
```

### Step 3: Test the command
```bash
php artisan attendance:mark-absent
```

### Step 4: Format with Pint
```bash
vendor/bin/pint --dirty --format agent app/Console/Commands/MarkAbsentEmployees.php
```

---

## 3️⃣ CONFIGURE EMAIL QUEUE (1.5 hours)

### Step 1: Check current config

**File:** `config/queue.php`

Verify default is set (database or redis):
```php
'default' => env('QUEUE_CONNECTION', 'database'),
```

### Step 2: Set up database queue

**In `.env`:**
```
QUEUE_CONNECTION=database
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS=noreply@hrms.local
MAIL_FROM_NAME="HRMS"
```

### Step 3: Run queue migration
```bash
php artisan queue:table
php artisan migrate
```

### Step 4: Start queue worker (development)
```bash
php artisan queue:work --max-attempts=3 --timeout=90
```

### Step 5: For production, use Supervisor or cron
```bash
# Add to crontab (processes queue every minute)
* * * * * cd /path/to/app && php artisan queue:work --max-attempts=3 --timeout=90 >> storage/logs/queue.log 2>&1
```

---

## 4️⃣ CREATE EMAIL TEMPLATES & SEND NOTIFICATIONS (2 hours)

### Step 1: Create notification classes

**Create:** `app/Notifications/UserApprovedNotification.php`

```php
<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Account Approved - HRMS')
            ->greeting("Hello {$notifiable->name},")
            ->line('Your account has been approved by an administrator.')
            ->line('You can now log in and complete your employee profile.')
            ->action('Complete Your Profile', route('employees.setup'))
            ->line('If you have any questions, please contact the HR department.');
    }
}
```

### Step 2: Create rejection notification

**Create:** `app/Notifications/UserRejectedNotification.php`

```php
<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserRejectedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $reason = 'Your application does not meet our requirements.'
    ) {}

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Application Status - HRMS')
            ->greeting("Hello {$notifiable->name},")
            ->line('Unfortunately, your application has been rejected.')
            ->line($this->reason)
            ->line('Please contact the HR department for more information.');
    }
}
```

### Step 3: Update UserRequestController

**File:** `app/Http/Controllers/UserRequestController.php`

Find the `approve()` method and add email:

```php
public function approve(UserRequest $request_model)
{
    $request_model->update([
        'status' => 'approved',
        'resolved_by' => auth()->id(),
        'resolved_at' => now(),
    ]);

    if ($request_model->type === 'Account Activation' && $request_model->user) {
        $request_model->user->update(['status' => 'active']);

        // Send email notification
        $request_model->user->notify(new \App\Notifications\UserApprovedNotification());

        HrmsNotification::create([
            'title' => 'Account Activated',
            'message' => 'Your account has been approved. Please complete your employee profile to get started.',
            'type' => 'success',
            'user_id' => $request_model->user->id,
        ]);

        return redirect()
            ->route('employees.setup', ['user' => $request_model->user->id])
            ->with('success', 'Account approved and email sent.');
    }

    return back()->with('success', 'Request approved.');
}

public function reject(UserRequest $request_model)
{
    $request_model->update([
        'status' => 'rejected',
        'resolved_by' => auth()->id(),
        'resolved_at' => now(),
    ]);

    if ($request_model->type === 'Account Activation' && $request_model->user) {
        $user = $request_model->user;
        $user->update(['status' => 'rejected']);

        // Send rejection email
        $user->notify(new \App\Notifications\UserRejectedNotification(
            'We are unable to process your registration at this time. Please contact HR for details.'
        ));

        HrmsNotification::create([
            'title' => 'Account Rejected',
            'message' => 'Your account registration has been rejected. Please contact support for more information.',
            'type' => 'error',
            'user_id' => $user->id,
        ]);
    }

    return back()->with('success', 'Request rejected and email sent.');
}
```

### Step 4: Format and test
```bash
vendor/bin/pint --dirty --format agent app/Http/Controllers/UserRequestController.php
vendor/bin/pint --dirty --format agent app/Notifications/UserApprovedNotification.php
vendor/bin/pint --dirty --format agent app/Notifications/UserRejectedNotification.php
```

---

## 5️⃣ IMPLEMENT LEAVE AUTO-ATTENDANCE (1 hour)

### Step 1: Update LeaveController

**File:** `app/Http/Controllers/LeaveController.php`

Find the `approve()` method and add auto-attendance logic:

```php
use App\Models\Attendance;
use Carbon\Carbon;

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

    // Auto-create ON_LEAVE attendance records for each day
    $startDate = Carbon::parse($leave->start_date);
    $endDate = Carbon::parse($leave->end_date);
    
    for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
        Attendance::firstOrCreate(
            [
                'employee_id' => $leave->employee_id,
                'date' => $date->toDateString(),
            ],
            [
                'status' => 'on_leave',
                'clock_in' => null,
                'clock_out' => null,
                'notes' => "On {$leave->type} leave",
            ]
        );
    }

    HrmsNotification::create([
        'title' => 'Leave Approved',
        'message' => "Your {$leave->type} leave ({$leave->start_date->format('M j')} – {$leave->end_date->format('M j')}) has been approved.",
        'type' => 'success',
        'user_id' => optional($leave->employee)->user_id,
    ]);

    return back()->with('success', 'Leave approved and attendance records created.');
}
```

### Step 2: Format with Pint
```bash
vendor/bin/pint --dirty --format agent app/Http/Controllers/LeaveController.php
```

### Step 3: Test the flow
- Create a test leave request
- Approve it
- Check attendance table for new ON_LEAVE records

---

## ✅ VERIFICATION AFTER IMPLEMENTATION

Run these tests to verify everything works:

```bash
# 1. Test the command manually
php artisan attendance:mark-absent

# 2. Check queue is working
php artisan queue:work

# 3. Test scheduler (in tinker)
php artisan tinker
>>> app(\Illuminate\Console\Scheduling\Schedule::class)->dueEvents(app())

# 4. Send test email
php artisan tinker
>>> auth()->user()->notify(new \App\Notifications\UserApprovedNotification())

# 5. Check database
>>> App\Models\Attendance::whereDate('date', today())->count()
>>> App\Models\Notification::latest()->first()
```

---

## 📋 CHECKLIST

- [ ] Added scheduler to `bootstrap/app.php`
- [ ] Created `MarkAbsentEmployees` command
- [ ] Tested command with `php artisan attendance:mark-absent`
- [ ] Configured email queue in `.env`
- [ ] Ran queue migrations
- [ ] Created `UserApprovedNotification`
- [ ] Created `UserRejectedNotification`
- [ ] Updated `UserRequestController` to send emails
- [ ] Updated `LeaveController` to create ON_LEAVE attendance
- [ ] Ran all files through Pint formatter
- [ ] Tested email sending with queue worker
- [ ] Verified attendance records created for approved leaves
- [ ] Ran all verification tests

---

## 🚀 NEXT: PHASE 2 - TIMESHEET ASSIGNMENTS

After completing Phase 1, you'll need to:

1. Create routes for AssignedTimesheet CRUD
2. Create controller for admin task assignment
3. Create views for admin UI (create/edit assigned tasks)
4. Create employee view for assigned tasks
5. Update timesheet submission to link to assigned tasks
6. Verify task status updates when timesheet submitted

Estimated: 4-5 hours

See `IMPLEMENTATION_REVIEW.md` for detailed Phase 2 steps.

---

**Completion Time:** ~6.5 hours  
**Difficulty:** Medium  
**Dependencies:** Laravel knowledge, Composer, PHP CLI

Good luck! 🚀

# 🚀 HRMS Pro - Developer Quick Reference
**Quick lookup guide for common tasks**

---

## 📍 Quick Links

| What | Where | How |
|------|-------|-----|
| **Start Server** | Terminal | `php artisan serve` (port 8000) |
| **Database** | Terminal | `php artisan migrate` |
| **View Routes** | Terminal | `php artisan route:list` |
| **Run Tests** | Terminal | `php artisan test --compact` |
| **Format Code** | Terminal | `vendor/bin/pint --dirty` |
| **Debug Shell** | Terminal | `php artisan tinker` |
| **Clear Cache** | Terminal | `php artisan cache:clear` |
| **View Logs** | Terminal | `php artisan pail` |

---

## 🎯 Role Quick Guide

```
┌─────────────────────────────────────────┐
│ SUPER_ADMIN                             │
│ ✓ Manage all employees                  │
│ ✓ Create sub-admins                     │
│ ✓ View all data                         │
│ ✓ System config                         │
└─────────────────────────────────────────┘

┌─────────────────────────────────────────┐
│ SUB_ADMIN (HR Manager)                  │
│ ✓ Manage employees (CRUD)               │
│ ✓ Approve leaves & timesheets           │
│ ✓ Create violations                     │
│ ✓ Write performance reviews             │
│ ✓ View dashboard & reports              │
│ ✗ Cannot create other admins            │
└─────────────────────────────────────────┘

┌─────────────────────────────────────────┐
│ EMPLOYEE                                │
│ ✓ Clock in/out                          │
│ ✓ Request leaves                        │
│ ✓ Submit timesheets                     │
│ ✓ View own performance                  │
│ ✓ View own violations                   │
│ ✗ Cannot approve anything               │
│ ✗ Cannot see other employees            │
└─────────────────────────────────────────┘
```

---

## 📊 Database Tables at a Glance

| Table | Purpose | Key Field |
|-------|---------|-----------|
| `users` | Login accounts | id, email, role, status |
| `employees` | Employee profiles | employee_id (EMP-0001) |
| `shifts` | Work hours | start_time, end_time, grace_period |
| `attendances` | Daily time tracking | time_in, time_out, status |
| `leaves` | Leave requests | type, status (pending/approved/denied) |
| `timesheets` | Weekly hours | total_hours, ot_hours |
| `violations` | Discipline records | level (Verbal→Termination) |
| `performances` | Reviews | score, rating, feedback |
| `hrms_notifications` | System messages | type, is_read |
| `user_requests` | Admin queue | status (pending/approved/rejected) |
| `assigned_timesheets` | Tasks | expected_hours, due_date |
| `audit_logs` | Compliance | action, changes, ip_address |

---

## 🔗 Common Routes

### Employee Self-Service
```
GET  /dashboard                    View dashboard
POST /attendance/time-in           Clock in
POST /attendance/time-out          Clock out
GET  /leaves                       View my leaves
POST /leaves                       Request leave
GET  /timesheets/my                View my timesheets
POST /timesheets                   Submit timesheet
GET  /performance/my               View my reviews
GET  /notifications                View notifications
```

### Admin Management
```
GET  /employees                    List all employees
POST /employees                    Create employee
GET  /employees/{id}               View employee profile
PATCH /employees/{id}              Edit employee
PATCH /employees/{id}/deactivate   Deactivate
PATCH /employees/{id}/activate     Reactivate

PATCH /leaves/{id}/approve         Approve leave
PATCH /leaves/{id}/deny            Deny leave

PATCH /timesheets/{id}/approve     Approve timesheet
PATCH /timesheets/{id}/reject      Reject timesheet

GET  /violations                   List violations
POST /violations                   Create violation
PATCH /violations/{id}/resolve     Resolve violation

POST /performance                  Create review

GET  /requests                     Pending approvals
PATCH /requests/{id}/approve       Approve request
PATCH /requests/{id}/reject        Reject request
```

### Super Admin Only
```
GET  /users/{id}/make-admin              Show form
PATCH /users/{id}/assign-admin-role      Assign role
```

---

## 🏗️ Model Structure

### Key Model Methods

```php
// USER
auth()->user()              // Current user
auth()->user()->isEmployee()
auth()->user()->isSubAdmin()
auth()->user()->isSuperAdmin()
auth()->user()->employee    // Get linked employee

// EMPLOYEE
$emp->fullName              // First + Last
$emp->initials              // e.g., "JD"
$emp->yearsOfService        // Years since hire_date
$emp->attendances           // All attendance records
$emp->leaves()              // All leave requests

// ATTENDANCE
$att->hoursWorked           // time_out - time_in
$att->employee              // Linked employee

// LEAVE
$leave->employee            // Linked employee
$leave->approver            // User who approved
```

---

## ⚡ Common Code Snippets

### Check if User is Admin
```php
if ($user->isSubAdmin() || $user->isSuperAdmin()) {
    // Admin access
}
```

### Get Current Employee
```php
$employee = auth()->user()->employee;
```

### Get Today's Attendance
```php
$attendance = Attendance::where('employee_id', $emp_id)
    ->whereDate('date', today())
    ->first();
```

### Create Notification
```php
HrmsNotification::create([
    'user_id' => $user_id,
    'title' => 'Leave Approved',
    'message' => 'Your leave has been approved',
    'type' => 'success'  // success, warning, error, info
]);
```

### Get Pending Leaves
```php
$pending = Leave::where('status', 'pending')
    ->with('employee.user')
    ->paginate(20);
```

### Record Time-In
```php
use App\Services\AttendanceService;

$attendance = AttendanceService::recordTimeIn($employee);
```

---

## 🎨 Frontend Components

### Clock In/Out Button
```blade
@if(!$todayAttendance)
    <form action="{{ route('attendance.time-in') }}" method="POST">
        @csrf
        <button class="btn btn-primary">⏱ Clock In</button>
    </form>
@endif
```

### Status Badge
```blade
<span class="badge badge-{{ $attendance->status }}">
    {{ ucfirst($attendance->status) }}
</span>
```

### Confirmation Dialog
```javascript
const confirmed = await ConfirmDialog.show(
    'Title',
    'Message',
    () => { /* callback */ }
);
```

---

## 🔐 Access Control

### Middleware Protection
```php
// In routes/web.php
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('employees', EmployeeController::class);
});

// Middleware aliases (bootstrap/app.php)
'admin' => IsAdmin::class          // sub_admin OR super_admin
'employee' => IsEmployee::class    // employee only
'super_admin' => SuperAdminOnly::class  // super_admin only
```

### Authorization in Controller
```php
public function update(Employee $employee) {
    $this->authorize('update', $employee);
    
    // Only admins OR employee's own record
    $employee->update($data);
}
```

---

## 🧪 Testing

### Run All Tests
```bash
php artisan test --compact
```

### Run Specific Test
```bash
php artisan test --compact --filter=testName
```

### Run Test File
```bash
php artisan test --compact tests/Feature/EmployeeTest.php
```

---

## 📝 Common Enums/Values

### User Roles
```
'super_admin'  Super Admin
'sub_admin'    Sub-Admin (HR Manager)
'employee'     Employee
```

### User Status
```
'active'       Active account
'inactive'     Deactivated
'pending'      Awaiting approval
'rejected'     Application rejected
```

### Employee Status
```
'active'       Full-time active
'probationary' On probation period
'contractual'  Contract basis
'inactive'     Not working
```

### Attendance Status
```
'present'   Clocked in on time
'late'      Clocked in after grace period
'absent'    No record
'half_day'  Left early or came very late
'on_leave'  Approved leave day
```

### Leave Types
```
'vacation'       Annual vacation (10 days)
'sick'           Sick leave (5 days)
'emergency'      Emergency (varies)
'maternity'      Maternity (60 days)
'paternity'      Paternity (7 days)
'solo_parent'    Solo parent (5 days)
```

### Leave Status
```
'pending'   Awaiting admin approval
'approved'  Approved by admin
'denied'    Rejected by admin
```

### Timesheet Status
```
'pending'   Submitted, waiting approval
'approved'  Approved for payroll
'rejected'  Rejected, can resubmit
```

### Violation Levels
```
'Verbal Warning'    First offense
'Written Warning'   Second offense
'Final Warning'     Third offense
'Suspension'        Fourth offense
'Termination'       Fifth offense
```

### Performance Ratings
```
'Outstanding'         Score 9-10
'Satisfactory'        Score 7-8
'Needs Improvement'   Score 5-6
'Poor'               Score <5
```

### Notification Types
```
'success'   Green - Positive action
'warning'   Yellow - Caution needed
'error'     Red - Problem
'info'      Blue - Information
```

---

## 🚨 Common Issues & Solutions

| Issue | Solution |
|-------|----------|
| "Page not found" | Clear cache: `php artisan cache:clear` |
| Middleware blocking access | Check role/status in `users` table |
| Employee not appearing | Check `employees` table has `profile_completed=1` |
| Time-in showing duplicate error | Check attendance already exists for today |
| CSS not updating | Run `npm run build` (production) or `npm run dev` (dev) |
| Email not sending | Queue not processing; check `.env` QUEUE_CONNECTION |
| Soft-deleted records showing | Add `->withoutTrashed()` to query |

---

## 📦 File Structure

```
laravel/HrMs/
├── app/
│   ├── Http/
│   │   ├── Controllers/     (13 controllers)
│   │   ├── Middleware/      (4 middleware)
│   │   └── Requests/        (form requests)
│   ├── Models/              (14 models)
│   └── Services/            (business logic)
├── database/
│   ├── migrations/          (schema)
│   ├── factories/           (testing)
│   └── seeders/             (sample data)
├── resources/
│   ├── views/               (blade templates)
│   ├── js/                  (frontend JS)
│   └── css/                 (styling)
├── routes/
│   ├── web.php              (main routes)
│   ├── auth.php             (auth routes)
│   └── console.php          (CLI commands)
├── bootstrap/
│   ├── app.php              (config)
│   └── providers.php        (services)
├── config/                  (all configs)
├── storage/                 (files, cache, logs)
├── tests/                   (unit & feature tests)
└── public/                  (served files)
```

---

## 💡 Development Workflow

### 1. Start Development
```bash
cd c:\Users\irish kaye dibdib\Desktop\laravel\HrMs
php artisan serve
# App running at http://127.0.0.1:8000
```

### 2. Create Feature
- Create migration: `php artisan make:migration table_name`
- Create model: `php artisan make:model ModelName`
- Create controller: `php artisan make:controller ControllerName`
- Add routes to `routes/web.php`
- Create blade templates

### 3. Test
- Run tests: `php artisan test --compact`
- Test routes: `php artisan route:list`

### 4. Format & Deploy
- Format code: `vendor/bin/pint --dirty`
- Clear cache: `php artisan cache:clear`
- Migrate (if needed): `php artisan migrate`

---

## 📞 Quick Admin Tasks

### Create New Employee (CLI)
```bash
php artisan tinker

// In tinker:
$user = User::factory()->create(['role' => 'employee']);
$employee = Employee::create([
    'user_id' => $user->id,
    'first_name' => 'John',
    'last_name' => 'Doe',
    'email' => $user->email,
    'employee_id' => 'EMP-' . str_pad(Employee::count() + 1, 4, '0', STR_PAD_LEFT),
    'department' => 'IT',
    'position' => 'Developer',
    'date_hired' => now(),
    'status' => 'active',
    'shift_id' => 1,
    'profile_completed' => true
]);
```

### Approve User Registration
```bash
// In UI: /requests → Find pending request → Approve
// OR in tinker:
$request = UserRequest::where('status', 'pending')->first();
$request->update(['status' => 'approved', 'resolved_by' => 1, 'resolved_at' => now()]);
User::find($request->user_id)->update(['status' => 'active']);
```

### Generate Demo Data
```bash
php artisan db:seed
```

---

**Last Updated: May 14, 2026**
**System: Laravel 12 | PHP 8.2 | MySQL**

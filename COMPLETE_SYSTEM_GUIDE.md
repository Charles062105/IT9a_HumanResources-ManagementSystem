# 🏢 HRMS Pro - Complete System Guide
**Laravel 12 | PHP 8.2 | MySQL | Tailwind CSS | Alpine.js**

---

## 📋 Table of Contents
1. [System Overview](#system-overview)
2. [Architecture & Tech Stack](#architecture--tech-stack)
3. [Complete Database Schema](#complete-database-schema)
4. [Data Models & Relationships](#data-models--relationships)
5. [Authentication & Authorization](#authentication--authorization)
6. [Complete API Routes](#complete-api-routes)
7. [Controllers & Endpoints](#controllers--endpoints)
8. [All User Workflows](#all-user-workflows)
9. [Frontend Structure](#frontend-structure)
10. [Services & Business Logic](#services--business-logic)
11. [Middleware System](#middleware-system)
12. [Security & Access Control](#security--access-control)
13. [Development Guide](#development-guide)

---

# System Overview

## What is HRMS Pro?

**HRMS Pro** is an enterprise-grade Human Resource Management System that automates the entire employee lifecycle:

```
┌──────────────────────────────────────────────────────────┐
│                   HRMS PRO ECOSYSTEM                      │
├──────────────────────────────────────────────────────────┤
│                                                            │
│  SUPER ADMIN (Organization Owner)                         │
│    ├─ Manage Sub-Admins                                   │
│    ├─ View All Organization Data                          │
│    └─ System Configuration                                │
│                                                            │
│  SUB-ADMIN (HR Manager)                                   │
│    ├─ Employee Management (CRUD)                          │
│    ├─ Leave Approvals                                     │
│    ├─ Timesheet Approvals                                 │
│    ├─ Performance Reviews                                 │
│    ├─ Violation Management                                │
│    └─ Dashboard & Reports                                 │
│                                                            │
│  EMPLOYEE                                                  │
│    ├─ Clock In/Out (Attendance)                           │
│    ├─ Submit Leave Requests                               │
│    ├─ Submit Timesheets                                   │
│    ├─ View Performance Reviews                            │
│    ├─ View Violations                                     │
│    └─ Personal Dashboard                                  │
│                                                            │
└──────────────────────────────────────────────────────────┘
```

---

# Architecture & Tech Stack

## Technology Stack

| Layer | Technology | Version |
|-------|-----------|---------|
| **Framework** | Laravel | 12.58.0 |
| **Language** | PHP | 8.2 |
| **Database** | MySQL | Latest |
| **Frontend CSS** | Tailwind CSS | 3.4.19 |
| **Frontend JS** | Alpine.js | 3.15.12 |
| **Package Manager** | Composer | Latest |
| **Node** | npm | Latest |
| **Testing** | PHPUnit | 11.5.55 |
| **Code Quality** | Laravel Pint | 1.29.1 |
| **Monitoring** | Laravel Pail | 1.2.6 |

## System Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                      BROWSER / CLIENT                        │
│              (Blade Templates + Alpine.js + Tailwind)        │
└────────────────────────────┬────────────────────────────────┘
                             │ HTTP Request
                             ↓
┌─────────────────────────────────────────────────────────────┐
│                    LARAVEL ROUTING LAYER                     │
│  • routes/web.php                                            │
│  • routes/auth.php                                           │
│  • routes/console.php                                        │
└────────────────────────────┬────────────────────────────────┘
                             │
                             ↓
┌─────────────────────────────────────────────────────────────┐
│                    MIDDLEWARE LAYER                          │
│  • IsAdmin, IsEmployee, SuperAdminOnly                       │
│  • CheckIncompleteProfile, CheckPermission                   │
│  • CSRF Protection, Authentication                           │
└────────────────────────────┬────────────────────────────────┘
                             │
                             ↓
┌─────────────────────────────────────────────────────────────┐
│                    CONTROLLER LAYER                          │
│  • AttendanceController                                      │
│  • EmployeeController                                        │
│  • LeaveController                                           │
│  • TimesheetController                                       │
│  • ViolationController                                       │
│  • PerformanceController                                     │
│  • DashboardController                                       │
│  • NotificationController                                    │
│  • UserRequestController                                     │
└────────────────────────────┬────────────────────────────────┘
                             │
                             ↓
┌─────────────────────────────────────────────────────────────┐
│                    SERVICE LAYER                             │
│  • AttendanceService (Business Logic)                        │
│  • Other services (in progress)                              │
└────────────────────────────┬────────────────────────────────┘
                             │
                             ↓
┌─────────────────────────────────────────────────────────────┐
│                    MODEL LAYER (Eloquent)                    │
│  • User, Employee, Attendance, Leave                         │
│  • Timesheet, Violation, Performance                         │
│  • Shift, Notification, UserRequest                          │
└────────────────────────────┬────────────────────────────────┘
                             │
                             ↓
┌─────────────────────────────────────────────────────────────┐
│                    DATABASE LAYER                            │
│  • MySQL Database                                            │
│  • 14 Tables with Relations                                  │
└─────────────────────────────────────────────────────────────┘
```

---

# Complete Database Schema

## 14 Core Tables

### 1. **users** - Authentication & User Accounts
```sql
id (PK)
name varchar(255)
email varchar(255) UNIQUE
email_verified_at timestamp (nullable)
password varchar(255) HASHED
role enum('super_admin', 'sub_admin', 'employee') DEFAULT 'employee'
status enum('active', 'inactive', 'pending', 'rejected') DEFAULT 'active'
remember_token varchar(100)
created_at, updated_at timestamp
```
**Purpose**: Core authentication & user identity  
**Roles**: 
- `super_admin`: Full system access, can create sub-admins
- `sub_admin`: HR manager, can manage employees
- `employee`: Regular employee access

---

### 2. **employees** - Employee Profiles
```sql
id (PK)
user_id (FK → users, CASCADE)
employee_id varchar(255) UNIQUE (Auto-generated: EMP-0001)
first_name, last_name varchar(255)
email varchar(255) UNIQUE
phone varchar(255)
date_of_birth date
address varchar(255)
department varchar(255)
position varchar(255)
date_hired date
status enum('active', 'probationary', 'contractual', 'inactive')
contract_expiry date (nullable)
sss_number, pagibig_number, philhealth_number varchar(255)
profile_completed boolean DEFAULT false
shift_id (FK → shifts)
deleted_at timestamp NULLABLE (Soft Delete)
created_at, updated_at timestamp
```
**Purpose**: Employee demographic & employment details  
**Key Features**:
- One-to-one relationship with users
- Soft deletes preserve history
- Gov't ID numbers tracking
- Shift assignment for attendance rules

---

### 3. **shifts** - Work Schedule Rules
```sql
id (PK)
name varchar(255) (e.g., "Morning Shift", "Night Shift")
description varchar(255)
start_time TIME (e.g., "08:30:00")
end_time TIME (e.g., "17:30:00")
grace_period_minutes int DEFAULT 15
overtime_threshold_minutes int DEFAULT 0
is_active boolean DEFAULT true
created_at, updated_at timestamp
```
**Purpose**: Defines work hours and grace periods  
**Example Shifts**:
- Morning: 08:30 - 17:30 (Grace: 15 min)
- Evening: 17:00 - 02:00 (Grace: 15 min)
- Night: 22:00 - 06:00 (Grace: 15 min)

---

### 4. **attendances** - Time Tracking Records
```sql
id (PK)
employee_id (FK → employees, CASCADE)
date DATE
time_in DATETIME (e.g., "2026-05-14 08:45:00")
time_out DATETIME (e.g., "2026-05-14 17:35:00")
status enum('present', 'late', 'absent', 'half_day', 'on_leave')
notes TEXT (nullable)
created_at, updated_at timestamp
```
**Purpose**: Daily attendance & hours tracking  
**Status Logic**:
- `present`: Clocked in within grace period
- `late`: Clocked in after grace period
- `absent`: No time_in recorded or marked absent
- `half_day`: Left early or came very late
- `on_leave`: On approved leave day

**Computed Fields**:
- `hoursWorked`: (time_out - time_in)
- `overtimeMinutes`: (hoursWorked - 8) * 60 if > threshold

---

### 5. **leaves** - Leave Request Management
```sql
id (PK)
employee_id (FK → employees, CASCADE)
type enum('vacation', 'sick', 'emergency', 'maternity', 'paternity', 'solo_parent')
start_date DATE
end_date DATE
days int (number of days requested)
reason TEXT
status enum('pending', 'approved', 'denied')
approved_by (FK → users, SET NULL)
approved_at TIMESTAMP (nullable)
created_at, updated_at timestamp
```
**Purpose**: Leave request workflow  
**Leave Types**: Vacation (10 days/year), Sick (5 days/year), Emergency, Maternity (60 days), Paternity (7 days), Solo Parent (5 days)

---

### 6. **timesheets** - Weekly Hour Submissions
```sql
id (PK)
employee_id (FK → employees, CASCADE)
week_start DATE (e.g., 2026-05-12)
week_end DATE (e.g., 2026-05-18)
week_label varchar(255) (e.g., "May 12-18, 2026")
total_hours decimal(5,1) (e.g., 40.5)
ot_hours decimal(5,1) (e.g., 2.5)
notes TEXT
rejection_reason TEXT (nullable)
status enum('pending', 'approved', 'rejected')
approved_by (FK → users, SET NULL)
submitted_at TIMESTAMP
assigned_timesheet_id (FK → assigned_timesheets)
created_at, updated_at timestamp
```
**Purpose**: Weekly timesheet submission & approval  
**Status Flow**: pending → approved OR rejected

---

### 7. **assigned_timesheets** - Assigned Timesheet Tasks
```sql
id (PK)
employee_id (FK → employees, CASCADE)
title varchar(255)
description TEXT
expected_hours decimal(5,1)
due_date DATE
status enum('pending', 'in_progress', 'submitted', 'approved', 'rejected')
admin_notes TEXT
approved_by (FK → users)
created_at, updated_at timestamp
```
**Purpose**: Track specific timesheet assignments from admin  

---

### 8. **violations** - Discipline & Offense Records
```sql
id (PK)
employee_id (FK → employees, CASCADE)
level enum('Verbal Warning', 'Written Warning', 'Final Warning', 'Suspension', 'Termination')
offense varchar(255)
description TEXT
date DATE
offense_count int DEFAULT 1
status enum('open', 'resolved')
issued_by (FK → users, RESTRICT)
created_at, updated_at timestamp
```
**Purpose**: Disciplinary action tracking  
**Escalation Path**:
```
First Offense → Verbal Warning
Second Offense → Written Warning
Third Offense → Final Warning
Fourth Offense → Suspension
Fifth Offense → Termination
```

---

### 9. **performances** - Performance Reviews
```sql
id (PK)
employee_id (FK → employees, CASCADE)
period varchar(255) (e.g., "Q1 2026", "Annual 2026")
score decimal(4,1) (1-10 scale)
rating enum('Outstanding', 'Satisfactory', 'Needs Improvement', 'Poor')
feedback TEXT
reviewed_by (FK → users, RESTRICT)
created_at, updated_at timestamp
```
**Purpose**: Employee performance evaluation  
**Rating Scale**:
- Outstanding: 9-10
- Satisfactory: 7-8
- Needs Improvement: 5-6
- Poor: 1-4

---

### 10. **hrms_notifications** - In-App Notifications
```sql
id (PK)
title varchar(255)
message TEXT
type enum('success', 'warning', 'error', 'info')
is_read boolean DEFAULT false
user_id (FK → users, CASCADE)
created_at, updated_at timestamp
```
**Purpose**: System notifications to users  

---

### 11. **user_requests** - Admin Request Queue
```sql
id (PK)
user_id (FK → users, CASCADE)
type enum('Account Activation', 'Role Change', 'Profile Update', 'Password Reset')
details TEXT
status enum('pending', 'approved', 'rejected')
resolved_by (FK → users, SET NULL)
resolved_at TIMESTAMP (nullable)
created_at, updated_at timestamp
```
**Purpose**: Track pending admin approvals  

---

### 12. **audit_logs** - System Audit Trail
```sql
id (PK)
admin_id (FK → users)
action varchar(255)
model_type varchar(255)
model_id bigint(20) unsigned
changes LONGTEXT (JSON)
description TEXT
ip_address varchar(255)
created_at, updated_at timestamp
```
**Purpose**: Compliance & security audit trail

---

### 13. **permissions** - Permission Definitions
```sql
id (PK)
name varchar(255)
description varchar(255)
created_at, updated_at timestamp
```
**Purpose**: Define system permissions (planned feature)

---

### 14. **role_permissions** - Permission Mapping
```sql
id (PK)
role varchar(255)
permission_id (FK → permissions)
created_at, updated_at timestamp
```
**Purpose**: Map roles to permissions (planned feature)

---

## Supporting Tables (Framework)
- **sessions**: User session storage
- **cache**: Application cache
- **cache_locks**: Cache locking
- **failed_jobs**: Failed queue jobs
- **jobs**: Queue jobs
- **job_batches**: Batch job tracking
- **migrations**: Schema migration history
- **password_reset_tokens**: Password reset tokens

---

# Data Models & Relationships

## Entity Relationship Diagram

```
┌────────────────────────────────────────────────────────────────────┐
│                          USER (Auth)                               │
│  ├─ has many: Employee (via user_id)                              │
│  ├─ has many: HrmsNotification (via user_id)                      │
│  ├─ has many: UserRequest (via user_id)                           │
│  ├─ has many: Leave (as approved_by)                              │
│  ├─ has many: Timesheet (as approved_by)                          │
│  ├─ has many: Violation (as issued_by)                            │
│  └─ has many: Performance (as reviewed_by)                        │
└────────────┬──────────────────────────────────────────────────────┘
             │ hasOne
             ↓
┌────────────────────────────────────────────────────────────────────┐
│                        EMPLOYEE                                     │
│  ├─ belongs to: User                                               │
│  ├─ belongs to: Shift                                              │
│  ├─ has many: Attendance (via employee_id)                         │
│  ├─ has many: Leave (via employee_id)                              │
│  ├─ has many: Timesheet (via employee_id)                          │
│  ├─ has many: Violation (via employee_id)                          │
│  ├─ has many: Performance (via employee_id)                        │
│  └─ has many: AssignedTimesheet (via employee_id)                  │
└────────────┬──────────────────────────────────────────────────────┘
             │
        ┌────┴──────────┬─────────────┬──────────────┬──────────────┐
        ↓               ↓             ↓              ↓              ↓
   ATTENDANCE      LEAVE         TIMESHEET      VIOLATION    PERFORMANCE
```

## Model Code Examples

### User Model
```php
class User extends Authenticatable {
    protected $fillable = ['name', 'email', 'password', 'role', 'status'];
    
    public function employee() {
        return $this->hasOne(Employee::class);
    }
    
    public function notifications() {
        return $this->hasMany(HrmsNotification::class);
    }
    
    public function isSuperAdmin() {
        return $this->role === 'super_admin';
    }
    
    public function isSubAdmin() {
        return $this->role === 'sub_admin';
    }
    
    public function isEmployee() {
        return $this->role === 'employee';
    }
}
```

### Employee Model
```php
class Employee extends Model {
    use SoftDeletes;
    
    protected $fillable = [
        'user_id', 'employee_id', 'first_name', 'last_name',
        'email', 'phone', 'address', 'department', 'position',
        'date_hired', 'date_of_birth', 'contract_expiry', 'status',
        'sss_number', 'pagibig_number', 'philhealth_number',
        'profile_completed', 'shift_id'
    ];
    
    protected $casts = [
        'date_hired' => 'date',
        'date_of_birth' => 'date',
        'contract_expiry' => 'date',
        'profile_completed' => 'boolean'
    ];
    
    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function shift() {
        return $this->belongsTo(Shift::class);
    }
    
    public function attendances() {
        return $this->hasMany(Attendance::class);
    }
    
    public function getFullNameAttribute() {
        return "{$this->first_name} {$this->last_name}";
    }
    
    public function getYearsOfServiceAttribute() {
        return now()->diffInYears($this->date_hired);
    }
}
```

### Attendance Model
```php
class Attendance extends Model {
    protected $fillable = ['employee_id', 'date', 'time_in', 'time_out', 'status', 'notes'];
    
    protected $casts = [
        'date' => 'date',
        'time_in' => 'datetime',
        'time_out' => 'datetime'
    ];
    
    public function employee() {
        return $this->belongsTo(Employee::class);
    }
    
    public function getHoursWorkedAttribute() {
        if (!$this->time_in || !$this->time_out) {
            return null;
        }
        return $this->time_out->diffInHours($this->time_in);
    }
}
```

---

# Authentication & Authorization

## Authentication Flow

```
USER VISITS /register
    ↓
Submit registration form
    ↓
System creates User (status: 'pending')
    ↓
Email verification link sent
    ↓
User clicks verification link
    ↓
Email verified_at timestamp set
    ↓
CheckIncompleteProfile middleware redirects
    ↓
USER VISITS /employees/setup
    ↓
Fill employee profile form
    ↓
System creates Employee record (profile_completed: true)
    ↓
Redirected to /pending (awaiting admin approval)
    ↓
ADMIN APPROVES (via /requests)
    ↓
User status → 'active'
    ↓
USER CAN LOGIN
```

## Authorization Levels

```
┌─────────────────────────────────────────────────────────┐
│            ROLE HIERARCHY                               │
└─────────────────────────────────────────────────────────┘

SUPER_ADMIN (Top Level)
  ├─ Can do everything
  ├─ Can create/manage sub-admins
  ├─ Can view all records
  ├─ Can view audit logs
  └─ System configuration

SUB_ADMIN (Manager Level)
  ├─ Cannot manage super-admins
  ├─ Can manage employees
  ├─ Can approve leaves/timesheets
  ├─ Can create violations
  ├─ Can write performance reviews
  ├─ Can view dashboards
  └─ Cannot create other sub-admins

EMPLOYEE (User Level)
  ├─ Can only see own records
  ├─ Can clock in/out
  ├─ Can submit leaves
  ├─ Can submit timesheets
  ├─ Can view own performance
  ├─ Can view own violations
  └─ Cannot approve anything
```

## Middleware Protection

```php
// In bootstrap/app.php
$middleware->alias([
    'admin' => IsAdmin::class,           // admin OR super_admin
    'employee' => IsEmployee::class,     // employee role
    'super_admin' => SuperAdminOnly::class,  // super_admin only
    'permission' => CheckPermission::class,  // custom permission
]);

// Applied to all web routes
$middleware->web(append: CheckIncompleteProfile::class);
```

---

# Complete API Routes

## Authentication Routes (routes/auth.php)

```php
GET    /register                    Show registration form
POST   /register                    Store new user
GET    /login                       Show login form
POST   /login                       Authenticate user
GET    /forgot-password             Show forgot password form
POST   /forgot-password             Send password reset email
GET    /reset-password/{token}      Show reset password form
POST   /reset-password              Update password with token
GET    /verify-email                Show email verification prompt
GET    /verify-email/{id}/{hash}    Verify email address
POST   /email/verification-notification  Resend verification email
GET    /pending                     Show pending approval page
POST   /confirm-password            Confirm password
PATCH  /password                    Update password (authenticated)
POST   /logout                      Logout user
```

## Public Routes (routes/web.php)

```php
GET    /                            Welcome page
GET    /dashboard                   Dashboard (admin/employee specific)
```

## Authenticated Routes (Middleware: 'auth')

### Profile Management
```php
GET    /profile                     Edit profile form
PATCH  /profile                     Update profile
DELETE /profile                     Delete account
```

### Employee Profile Setup
```php
GET    /employees/setup             Show setup form (post-registration)
POST   /employees/setup             Save employee profile
```

### Resource Routes (All Authenticated Users)
```php
# Attendance
GET    /attendance                  List attendances
POST   /attendance                  Create attendance
GET    /attendance/{id}             View attendance
GET    /attendance/{id}/edit        Edit form
PATCH  /attendance/{id}             Update attendance
DELETE /attendance/{id}             Delete attendance
POST   /attendance/time-in          Clock in
POST   /attendance/time-out         Clock out

# Leaves
GET    /leaves                      List leaves
POST   /leaves                      Create leave request
GET    /leaves/{id}                 View leave
GET    /leaves/{id}/edit            Edit form
PATCH  /leaves/{id}                 Update leave
DELETE /leaves/{id}                 Delete leave

# Timesheets
GET    /timesheets                  List timesheets
POST   /timesheets                  Submit timesheet
GET    /timesheets/{id}             View timesheet
GET    /timesheets/{id}/edit        Edit form
PATCH  /timesheets/{id}             Update timesheet
DELETE /timesheets/{id}             Delete timesheet
GET    /timesheets/my               View own timesheets

# Violations
GET    /violations                  List violations
POST   /violations                  Create violation
GET    /violations/{id}             View violation
GET    /violations/{id}/edit        Edit form
PATCH  /violations/{id}             Update violation
DELETE /violations/{id}             Delete violation
PATCH  /violations/{id}/resolve     Resolve violation

# Performance
GET    /performance                 List performance reviews
POST   /performance                 Create review
GET    /performance/{id}            View review
GET    /performance/{id}/edit       Edit form
PATCH  /performance/{id}            Update review
DELETE /performance/{id}            Delete review
GET    /performance/my              View own reviews

# Notifications
GET    /notifications               List notifications
POST   /notifications               Create notification
GET    /notifications/{id}          View notification
DELETE /notifications/{id}          Delete notification
PATCH  /notifications/{id}/read     Mark as read
POST   /notifications/read-all      Mark all as read
```

## Admin Routes (Middleware: 'auth', 'admin')

```php
# Employee Management
GET    /employees                   List all employees
POST   /employees                   Create employee
GET    /employees/{id}              View employee
GET    /employees/{id}/edit         Edit form
PATCH  /employees/{id}              Update employee
DELETE /employees/{id}              Delete employee
PATCH  /employees/{id}/deactivate   Deactivate employee
PATCH  /employees/{id}/activate     Reactivate employee
POST   /employees/batch/deactivate  Batch deactivate
POST   /employees/batch/export      Export employees

# Leave Approvals
PATCH  /leaves/{id}/approve         Approve leave request
PATCH  /leaves/{id}/deny            Deny leave request

# Timesheet Approvals
PATCH  /timesheets/{id}/approve     Approve timesheet
PATCH  /timesheets/{id}/reject      Reject timesheet

# Assigned Timesheets
GET    /assigned-timesheets         List assigned tasks
POST   /assigned-timesheets         Create assignment
GET    /assigned-timesheets/{id}    View assignment
PATCH  /assigned-timesheets/{id}    Update assignment
DELETE /assigned-timesheets/{id}    Delete assignment

# Admin Requests
GET    /requests                    List pending requests
PATCH  /requests/{id}/approve       Approve request
PATCH  /requests/{id}/reject        Reject request
```

## Super Admin Routes (Middleware: 'auth', 'super_admin')

```php
GET    /users/{id}/make-admin                Show assign admin form
PATCH  /users/{id}/assign-admin-role        Grant admin role
PATCH  /employees/{employee}/role           Update employee role
```

---

# Controllers & Endpoints

## DashboardController

### `index()` - Route: GET /dashboard
```php
public function index() {
    $user = auth()->user();
    
    if ($user->isSuperAdmin() || $user->isSubAdmin()) {
        return $this->adminDashboard($user);
    } else {
        return $this->employeeDashboard($user);
    }
}
```

**Admin Dashboard Returns**:
- Total employees
- Employees present today
- Employees late today
- Pending leave requests
- Open violations
- 7-day attendance chart
- Pending leave list with actions
- Recent activity log

**Employee Dashboard Returns**:
- Today's attendance status
- Leave balance
- Recent performance reviews
- Open violations
- Upcoming leaves

---

## AttendanceController

### `index()` - Route: GET /attendance
```php
public function index(Request $request) {
    $query = Attendance::query();
    
    if (!$user->isAdmin()) {
        $query->whereHas('employee', fn($q) 
            => $q->where('user_id', $user->id)
        );
    }
    
    return view('attendance.index', [
        'attendances' => $query->paginate(15)
    ]);
}
```

### `timeIn()` - Route: POST /attendance/time-in
```php
public function timeIn(Request $request) {
    $employee = Employee::find($employeeId);
    
    try {
        $attendance = AttendanceService::recordTimeIn($employee);
        return back()->with('success', 'Time-in recorded');
    } catch (Exception $e) {
        return back()->with('error', 'Already clocked in');
    }
}
```

**Logic**:
1. Check if employee already clocked in today
2. Create attendance record with current time
3. Determine status (present/late) based on shift
4. Show success/error message

### `timeOut()` - Route: POST /attendance/time-out
```php
public function timeOut(Request $request) {
    $attendance = Attendance::where('employee_id', $id)
        ->whereDate('date', today())
        ->firstOrFail();
    
    $attendance->update([
        'time_out' => now(),
        'notes' => $request->notes
    ]);
    
    return back()->with('success', 'Time-out recorded');
}
```

### `markAbsent()` - Route: POST /attendance/mark-absent
**Admin Only**: Manually mark employee as absent

---

## EmployeeController

### `index()` - Route: GET /employees (Admin Only)
```php
public function index(Request $request) {
    $query = Employee::with('user', 'shift')
        ->when($request->search, fn($q) 
            => $q->where('first_name', 'like', "%{$request->search}%")
                 ->orWhere('last_name', 'like', "%{$request->search}%")
        )
        ->when($request->department, fn($q) 
            => $q->where('department', $request->department)
        )
        ->when($request->status, fn($q) 
            => $q->where('status', $request->status)
        );
    
    return view('employees.index', [
        'employees' => $query->paginate(20)
    ]);
}
```

**Features**:
- Search by name
- Filter by department
- Filter by status
- Pagination (20 per page)

### `store()` - Route: POST /employees (Admin Only)
```php
public function store(Request $request) {
    $validated = $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|unique:employees',
        'phone' => 'required|string',
        'date_of_birth' => 'required|date',
        'address' => 'required|string',
        'department' => 'required|string',
        'position' => 'required|string',
        'date_hired' => 'required|date',
        'shift_id' => 'required|exists:shifts,id',
        'status' => 'required|in:active,probationary,contractual',
        // ...gov't IDs
    ]);
    
    $employee = Employee::create($validated);
    return redirect()->route('employees.show', $employee);
}
```

### `deactivate()` - Route: PATCH /employees/{id}/deactivate
```php
public function deactivate(Employee $employee) {
    $employee->update(['status' => 'inactive']);
    return back()->with('success', 'Employee deactivated');
}
```

### `activate()` - Route: PATCH /employees/{id}/activate
```php
public function activate(Employee $employee) {
    $employee->update(['status' => 'active']);
    return back()->with('success', 'Employee activated');
}
```

---

## LeaveController

### `store()` - Route: POST /leaves
```php
public function store(Request $request) {
    $validated = $request->validate([
        'type' => 'required|in:vacation,sick,emergency,maternity,paternity,solo_parent',
        'start_date' => 'required|date|after_or_equal:today',
        'end_date' => 'required|date|after_or_equal:start_date',
        'reason' => 'required|string|max:500'
    ]);
    
    $days = Carbon::parse($validated['end_date'])
        ->diffInDays(Carbon::parse($validated['start_date'])) + 1;
    
    Leave::create([
        ...$validated,
        'employee_id' => $request->user()->employee->id,
        'days' => $days,
        'status' => 'pending'
    ]);
    
    return back()->with('success', 'Leave request submitted');
}
```

### `approve()` - Route: PATCH /leaves/{id}/approve (Admin Only)
```php
public function approve(Leave $leave) {
    $leave->update([
        'status' => 'approved',
        'approved_by' => auth()->id(),
        'approved_at' => now()
    ]);
    
    // Mark attendance as on_leave for those dates
    for ($date = $leave->start_date; $date <= $leave->end_date; $date->addDay()) {
        Attendance::firstOrCreate([
            'employee_id' => $leave->employee_id,
            'date' => $date
        ], ['status' => 'on_leave']);
    }
    
    HrmsNotification::create([
        'user_id' => $leave->employee->user_id,
        'title' => 'Leave Approved',
        'message' => "Your {$leave->type} leave has been approved",
        'type' => 'success'
    ]);
}
```

### `deny()` - Route: PATCH /leaves/{id}/deny (Admin Only)
```php
public function deny(Leave $leave) {
    $leave->update(['status' => 'denied']);
    
    HrmsNotification::create([
        'user_id' => $leave->employee->user_id,
        'title' => 'Leave Denied',
        'message' => 'Your leave request was not approved',
        'type' => 'warning'
    ]);
}
```

---

## TimesheetController

### `store()` - Route: POST /timesheets
```php
public function store(Request $request) {
    $validated = $request->validate([
        'week_start' => 'required|date',
        'week_end' => 'required|date|after:week_start',
        'total_hours' => 'required|numeric|between:0,60',
        'ot_hours' => 'required|numeric|between:0,20',
        'notes' => 'nullable|string'
    ]);
    
    $timesheet = Timesheet::create([
        ...$validated,
        'employee_id' => $request->user()->employee->id,
        'week_label' => $this->generateWeekLabel($validated['week_start'], $validated['week_end']),
        'status' => 'pending',
        'submitted_at' => now()
    ]);
    
    return back()->with('success', 'Timesheet submitted');
}
```

### `approve()` - Route: PATCH /timesheets/{id}/approve (Admin Only)
```php
public function approve(Timesheet $timesheet) {
    $timesheet->update([
        'status' => 'approved',
        'approved_by' => auth()->id()
    ]);
    
    HrmsNotification::create([
        'user_id' => $timesheet->employee->user_id,
        'title' => 'Timesheet Approved',
        'message' => "Your timesheet for {$timesheet->week_label} is approved",
        'type' => 'success'
    ]);
}
```

---

## ViolationController

### `store()` - Route: POST /violations (Admin Only)
```php
public function store(Request $request) {
    $validated = $request->validate([
        'employee_id' => 'required|exists:employees,id',
        'level' => 'required|in:Verbal Warning,Written Warning,Final Warning,Suspension,Termination',
        'offense' => 'required|string',
        'description' => 'required|string',
        'date' => 'required|date'
    ]);
    
    $violation = Violation::create([
        ...$validated,
        'issued_by' => auth()->id(),
        'status' => 'open'
    ]);
    
    HrmsNotification::create([
        'user_id' => $violation->employee->user_id,
        'title' => 'Violation Issued',
        'message' => "You received a {$violation->level}",
        'type' => 'warning'
    ]);
}
```

---

## PerformanceController

### `store()` - Route: POST /performance (Admin Only)
```php
public function store(Request $request) {
    $validated = $request->validate([
        'employee_id' => 'required|exists:employees,id',
        'period' => 'required|string',
        'score' => 'required|numeric|between:1,10',
        'feedback' => 'required|string'
    ]);
    
    // Auto-determine rating based on score
    $rating = match (true) {
        $validated['score'] >= 9 => 'Outstanding',
        $validated['score'] >= 7 => 'Satisfactory',
        $validated['score'] >= 5 => 'Needs Improvement',
        default => 'Poor'
    };
    
    Performance::create([
        ...$validated,
        'rating' => $rating,
        'reviewed_by' => auth()->id()
    ]);
}
```

---

## NotificationController

### `index()` - Route: GET /notifications
```php
public function index() {
    $notifications = HrmsNotification::where('user_id', auth()->id())
        ->latest()
        ->paginate(20);
    
    return view('notifications.index', ['notifications' => $notifications]);
}
```

### `markRead()` - Route: PATCH /notifications/{id}/read
```php
public function markRead(HrmsNotification $notification) {
    $this->authorize('update', $notification);
    
    $notification->update(['is_read' => true]);
    return back()->with('success', 'Marked as read');
}
```

### `readAll()` - Route: POST /notifications/read-all
```php
public function readAll() {
    HrmsNotification::where('user_id', auth()->id())
        ->update(['is_read' => true]);
    
    return back()->with('success', 'All notifications marked as read');
}
```

---

## UserRequestController

### `index()` - Route: GET /requests (Admin Only)
```php
public function index(Request $request) {
    $requests = UserRequest::when($request->status, fn($q) 
        => $q->where('status', $request->status)
    )->paginate(20);
    
    return view('requests.index', ['requests' => $requests]);
}
```

### `approve()` - Route: PATCH /requests/{id}/approve (Admin Only)
```php
public function approve(UserRequest $request) {
    $request->update([
        'status' => 'approved',
        'resolved_by' => auth()->id(),
        'resolved_at' => now()
    ]);
    
    // Handle specific request types
    if ($request->type === 'Account Activation') {
        User::find($request->user_id)->update(['status' => 'active']);
    }
    
    HrmsNotification::create([
        'user_id' => $request->user_id,
        'title' => 'Request Approved',
        'message' => "{$request->type} request has been approved",
        'type' => 'success'
    ]);
}
```

### `makeAdmin()` - Route: GET /users/{id}/make-admin (Super Admin Only)
Show form to assign admin role

### `assignAdminRole()` - Route: PATCH /users/{id}/assign-admin-role (Super Admin Only)
```php
public function assignAdminRole(User $user, Request $request) {
    $validated = $request->validate([
        'role' => 'required|in:sub_admin,super_admin'
    ]);
    
    $user->update(['role' => $validated['role']]);
    
    HrmsNotification::create([
        'user_id' => $user->id,
        'title' => 'Role Updated',
        'message' => "You have been granted {$validated['role']} privileges",
        'type' => 'success'
    ]);
}
```

---

# All User Workflows

## 1. New Employee Registration & Onboarding

```
STEP 1: USER REGISTRATION
┌─────────────────────────────────────────┐
│ User visits /register                   │
│ Fills: Name, Email, Password            │
│ System creates User record              │
│ • status: 'pending'                     │
│ • role: 'employee'                      │
│ • email_verified_at: NULL               │
└─────────────────────────────────────────┘
                   ↓
STEP 2: EMAIL VERIFICATION
┌─────────────────────────────────────────┐
│ Verification email sent                 │
│ User clicks verification link           │
│ email_verified_at timestamp set         │
└─────────────────────────────────────────┘
                   ↓
STEP 3: PROFILE SETUP
┌─────────────────────────────────────────┐
│ CheckIncompleteProfile redirects        │
│ User visits /employees/setup            │
│ Fills:                                  │
│ • First/Last Name                       │
│ • DOB, Phone, Address                   │
│ • Department, Position                  │
│ • Gov't IDs (SSS, PAG-IBIG, PhilHealth) │
│ System creates Employee record          │
│ • profile_completed: true               │
│ • status: 'active'                      │
│ Employee ID auto-generated (EMP-0001)   │
└─────────────────────────────────────────┘
                   ↓
STEP 4: ADMIN REVIEW
┌─────────────────────────────────────────┐
│ UserRequest created (Account Activation)│
│ Status: 'pending'                       │
│ User redirected to /pending page        │
│ Cannot login yet                        │
│                                         │
│ ADMIN VIEWS: /requests                  │
│ ADMIN CLICKS: Approve                   │
│ User status → 'active'                  │
│ Notification sent to employee           │
└─────────────────────────────────────────┘
                   ↓
STEP 5: EMPLOYEE LOGIN & FIRST USE
┌─────────────────────────────────────────┐
│ User can now login                      │
│ Redirected to dashboard                 │
│ Can clock in/out                        │
│ Can submit leave requests                │
│ Full HRMS access granted                │
└─────────────────────────────────────────┘
```

## 2. Daily Clock In/Out Workflow

```
MORNING: EMPLOYEE ARRIVES
┌─────────────────────────────────────────┐
│ Employee visits /attendance             │
│ Clicks "Clock In" button                │
│ Current time recorded (e.g., 08:45)     │
│                                         │
│ SYSTEM CHECKS:                          │
│ • Employee shift start time: 08:30      │
│ • Grace period: 15 minutes              │
│ • 08:45 > 08:45 (08:30 + 15)? NO       │
│ → Status: 'present'                     │
└─────────────────────────────────────────┘
                OR
              (Late)
┌─────────────────────────────────────────┐
│ If clocked in at 09:00                  │
│ • 09:00 > 08:45? YES                    │
│ → Status: 'late'                        │
│ • Attendance record flagged as late     │
│ • May trigger violation if repeat       │
└─────────────────────────────────────────┘

AFTERNOON: EMPLOYEE LEAVES
┌─────────────────────────────────────────┐
│ Employee clicks "Clock Out"             │
│ Time recorded (e.g., 17:45)             │
│                                         │
│ SYSTEM CALCULATES:                      │
│ • Hours worked: 17:45 - 08:45 = 9 hrs  │
│ • Overtime: 9 hrs - 8 hrs = 1 hr OT    │
│ • If OT > threshold, flag for pay       │
│                                         │
│ Attendance record COMPLETE              │
│ Dashboard updated                       │
└─────────────────────────────────────────┘
```

## 3. Leave Request Workflow

```
EMPLOYEE REQUESTS LEAVE
┌─────────────────────────────────────────┐
│ Visits /leaves → "Create New"           │
│ Fills form:                             │
│ • Type: vacation, sick, emergency etc   │
│ • Start Date: 2026-05-20                │
│ • End Date: 2026-05-22                  │
│ • Reason: "Family vacation"             │
│                                         │
│ System calculates:                      │
│ • Days = 3                              │
│ • Status = 'pending'                    │
│                                         │
│ Submitted                               │
└─────────────────────────────────────────┘
                   ↓
ADMIN REVIEWS
┌─────────────────────────────────────────┐
│ Admin dashboard shows pending leaves    │
│ Admin clicks on request                 │
│ Reviews: dates, type, reason            │
│ Decides: APPROVE or DENY                │
└─────────────────────────────────────────┘
                   ↓
IF APPROVED
┌─────────────────────────────────────────┐
│ Leave status → 'approved'               │
│ approved_by & approved_at set           │
│                                         │
│ SYSTEM AUTO-MARKS ATTENDANCE:           │
│ • 2026-05-20: status = 'on_leave'      │
│ • 2026-05-21: status = 'on_leave'      │
│ • 2026-05-22: status = 'on_leave'      │
│                                         │
│ Notification sent to employee:          │
│ "Your vacation leave approved!"         │
│ Employee can't clock in those days      │
└─────────────────────────────────────────┘
                OR
              (Denied)
┌─────────────────────────────────────────┐
│ Leave status → 'denied'                 │
│ Employee notified                       │
│ Can resubmit new request                │
└─────────────────────────────────────────┘
```

## 4. Timesheet Submission & Approval

```
EMPLOYEE SUBMITS TIMESHEET
┌─────────────────────────────────────────┐
│ Visits /timesheets → "Create"           │
│ Week: May 12-18, 2026                   │
│ Total hours: 40.5 (includes 0.5 OT)     │
│ OT hours: 1.5                           │
│ Notes: "Covered for John on Monday"     │
│                                         │
│ Status: 'pending'                       │
│ submitted_at: now()                     │
└─────────────────────────────────────────┘
                   ↓
ADMIN APPROVES/REJECTS
┌─────────────────────────────────────────┐
│ Dashboard shows "Pending Timesheets"    │
│ Admin reviews details                   │
│                                         │
│ OPTION 1: APPROVE                       │
│ • Status → 'approved'                   │
│ • approved_by → admin ID                │
│ • Timesheet ready for payroll           │
│ • Notification: "Timesheet Approved"    │
│                                         │
│ OPTION 2: REJECT                        │
│ • Status → 'rejected'                   │
│ • rejection_reason: "Hours exceed 60"   │
│ • Employee can resubmit                 │
│ • Notification: "Timesheet Rejected"    │
└─────────────────────────────────────────┘
```

## 5. Violation Escalation Workflow

```
VIOLATION ISSUED BY ADMIN
┌─────────────────────────────────────────┐
│ Admin creates violation                 │
│ Employee: John Doe                      │
│ Offense: "Tardiness"                    │
│ Level: "Verbal Warning" (1st)           │
│ offense_count: 1                        │
│ status: 'open'                          │
│ issued_by: admin_id                     │
│                                         │
│ Notification to employee:               │
│ "You received a Verbal Warning"         │
└─────────────────────────────────────────┘
                   ↓
FIRST REPEAT OFFENSE
┌─────────────────────────────────────────┐
│ Admin creates new violation             │
│ Same employee, same offense             │
│ Level: "Written Warning" (2nd)          │
│ offense_count: 2                        │
│ status: 'open'                          │
│                                         │
│ Employee dashboard shows:               │
│ • 1x Verbal Warning (resolved)          │
│ • 1x Written Warning (open)             │
└─────────────────────────────────────────┘
                   ↓
ESCALATION CONTINUES
┌─────────────────────────────────────────┐
│ 3rd Offense → Final Warning             │
│ 4th Offense → Suspension                │
│ 5th Offense → Termination               │
│                                         │
│ Admin can RESOLVE violations            │
│ status: 'resolved'                      │
│ Shows disciplinary history              │
└─────────────────────────────────────────┘
```

## 6. Performance Review Process

```
ADMIN CREATES REVIEW
┌─────────────────────────────────────────┐
│ Visits /performance → "Create"          │
│ Employee: Jane Smith                    │
│ Period: "Q1 2026"                       │
│ Score: 8.5 (out of 10)                  │
│ Feedback:                               │
│ "Excellent performance this quarter.    │
│  Shows great initiative and teamwork.   │
│  Keep it up!"                           │
│                                         │
│ SYSTEM AUTO-RATES:                      │
│ 8.5 → 'Satisfactory' rating            │
│ (9-10: Outstanding, 7-8: Satisfactory) │
│ (5-6: Needs Improvement, <5: Poor)     │
└─────────────────────────────────────────┘
                   ↓
EMPLOYEE VIEWS REVIEW
┌─────────────────────────────────────────┐
│ Employee visits /performance/my         │
│ Sees:                                   │
│ • Review score: 8.5                     │
│ • Rating: Satisfactory                  │
│ • Feedback from manager                 │
│ • Period: Q1 2026                       │
│ • Reviewed by: [Manager Name]           │
│                                         │
│ Can download review for records         │
└─────────────────────────────────────────┘
```

## 7. Admin Role Assignment (Super Admin Only)

```
SUPER ADMIN ASSIGNS SUB-ADMIN ROLE
┌─────────────────────────────────────────┐
│ Super Admin visits /users/{id}/make-admin
│ Shows form for role assignment          │
│ Options: sub_admin, super_admin         │
│                                         │
│ Clicks "Confirm"                        │
│ Confirmation dialog shows:              │
│ "Assign sub_admin role to John Doe?"    │
└─────────────────────────────────────────┘
                   ↓
ROLE UPDATED
┌─────────────────────────────────────────┐
│ User.role updated from 'employee'       │
│ to 'sub_admin'                          │
│                                         │
│ New Permissions Granted:                │
│ ✓ Access /employees (manage employees)  │
│ ✓ Access /leaves (approve)              │
│ ✓ Access /timesheets (approve)          │
│ ✓ Access /violations (manage)           │
│ ✓ Access /requests (approve signups)    │
│ ✓ View admin dashboard                  │
│                                         │
│ Notification sent:                      │
│ "You have been promoted to Sub-Admin"   │
│ Previous employee data still visible    │
└─────────────────────────────────────────┘
```

---

# Frontend Structure

## File Organization

```
resources/
├── views/
│   ├── layouts/
│   │   └── app.blade.php          Master layout
│   ├── dashboard/
│   │   ├── admin.blade.php        Admin dashboard
│   │   └── employee.blade.php     Employee dashboard
│   ├── attendance/
│   │   ├── index.blade.php        List attendance
│   │   └── clock-in-out.blade.php Clock in/out UI
│   ├── employees/
│   │   ├── index.blade.php        Employee list
│   │   ├── create.blade.php       Create form
│   │   ├── edit.blade.php         Edit form
│   │   └── show.blade.php         Employee profile
│   ├── leaves/
│   │   ├── index.blade.php        Leave list
│   │   └── create.blade.php       Request form
│   ├── timesheets/
│   │   ├── index.blade.php        Timesheet list
│   │   └── create.blade.php       Submit form
│   ├── violations/
│   │   ├── index.blade.php        Violation list
│   │   └── show.blade.php         Details
│   ├── performance/
│   │   ├── index.blade.php        Review list
│   │   └── show.blade.php         Review details
│   └── notifications/
│       └── index.blade.php        Notification list
├── js/
│   ├── app.js                     Main JS file
│   ├── admin-role-handler.js      Admin role modal
│   ├── components.js              Shared components
│   └── ...other modules
└── css/
    └── app.css                    Tailwind CSS

Bootstrap Files:
bootstrap/app.php                  App configuration
bootstrap/providers.php            Service providers

Config Files:
config/app.php
config/database.php
config/auth.php
...etc
```

## Frontend Components

### 1. Clock In/Out Component
```html
<div class="card">
    <h3>Time Tracking</h3>
    @if(!$todayAttendance)
        <form action="{{ route('attendance.time-in') }}" method="POST">
            <button type="submit" class="btn-primary">
                ⏱ Clock In
            </button>
        </form>
    @elseif(!$todayAttendance->time_out)
        <p>Clocked in at: {{ $todayAttendance->time_in->format('H:i') }}</p>
        
        <form action="{{ route('attendance.time-out') }}" method="POST">
            <textarea name="notes" placeholder="Notes"></textarea>
            <button type="submit" class="btn-danger">
                ⏹ Clock Out
            </button>
        </form>
    @else
        <p>Hours worked: {{ $todayAttendance->hours_worked }}</p>
    @endif
</div>
```

### 2. Leave Request Form
```html
<form action="{{ route('leaves.store') }}" method="POST">
    <div class="form-group">
        <label>Leave Type</label>
        <select name="type" required>
            <option value="">Select...</option>
            <option value="vacation">Vacation</option>
            <option value="sick">Sick Leave</option>
            <option value="emergency">Emergency</option>
            <option value="maternity">Maternity</option>
            <option value="paternity">Paternity</option>
            <option value="solo_parent">Solo Parent</option>
        </select>
    </div>
    
    <div class="form-group">
        <label>Start Date</label>
        <input type="date" name="start_date" required>
    </div>
    
    <div class="form-group">
        <label>End Date</label>
        <input type="date" name="end_date" required>
    </div>
    
    <div class="form-group">
        <label>Reason</label>
        <textarea name="reason" required></textarea>
    </div>
    
    <button type="submit" class="btn-primary">Submit Request</button>
</form>
```

### 3. Admin Dashboard Widgets
```html
<!-- KPI Cards -->
<div class="grid grid-cols-4 gap-4">
    <div class="card">
        <h4>Total Employees</h4>
        <p class="text-3xl">{{ $totalEmployees }}</p>
    </div>
    <div class="card">
        <h4>Present Today</h4>
        <p class="text-3xl text-green-600">{{ $presentToday }}</p>
    </div>
    <div class="card">
        <h4>Late Today</h4>
        <p class="text-3xl text-red-600">{{ $lateToday }}</p>
    </div>
    <div class="card">
        <h4>Pending Leaves</h4>
        <p class="text-3xl text-yellow-600">{{ $pendingLeaves }}</p>
    </div>
</div>

<!-- Attendance Chart -->
<canvas id="attendanceChart"></canvas>

<!-- Pending Actions List -->
<div class="pending-actions">
    <h4>Pending Approvals</h4>
    <table>
        <tr>
            <th>Type</th>
            <th>Employee</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        @foreach($pendingLeaves as $leave)
            <tr>
                <td>{{ $leave->type }}</td>
                <td>{{ $leave->employee->fullName }}</td>
                <td>{{ $leave->start_date }} - {{ $leave->end_date }}</td>
                <td>
                    <form action="{{ route('leaves.approve', $leave) }}" method="POST" style="display:inline;">
                        <button type="submit" class="btn-success">Approve</button>
                    </form>
                    <form action="{{ route('leaves.deny', $leave) }}" method="POST" style="display:inline;">
                        <button type="submit" class="btn-danger">Deny</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
</div>
```

### 4. JavaScript Components (admin-role-handler.js)
```javascript
export class AdminRoleHandler {
    static initMakeAdmin() {
        const form = document.getElementById('adminRoleForm');
        if (!form) return;
        
        this.setupFormTracking(form);
        this.setupMakeAdminSubmit(form);
    }
    
    static setupMakeAdminSubmit(form) {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const roleLabel = form.querySelector('.radio-title').textContent.trim();
            const userName = form.dataset.userName || 'this user';
            
            const confirmed = await ConfirmDialog.show(
                'Assign Admin Role',
                `You are assigning ${roleLabel} role to ${userName}. Proceed?`,
                () => {
                    form.dataset.submitting = '1';
                    LoadingOverlay.show('Assigning role...');
                    form.submit();
                }
            );
        });
    }
}
```

---

# Services & Business Logic

## AttendanceService

```php
namespace App\Services;

use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;

class AttendanceService {
    
    /**
     * Record employee time-in
     */
    public static function recordTimeIn(Employee $employee): Attendance {
        try {
            // Check if already clocked in today
            $exists = Attendance::where('employee_id', $employee->id)
                ->whereDate('date', today())
                ->first();
            
            if ($exists) {
                throw new Exception('Already clocked in today');
            }
            
            // Create attendance record
            $attendance = Attendance::create([
                'employee_id' => $employee->id,
                'date' => today(),
                'time_in' => now(),
                'status' => self::determineStatus($employee, now())
            ]);
            
            return $attendance;
            
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                throw new Exception('Duplicate entry for today');
            }
            throw $e;
        }
    }
    
    /**
     * Record employee time-out
     */
    public static function recordTimeOut(Employee $employee, Carbon $time = null): Attendance {
        $attendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', today())
            ->firstOrFail();
        
        if ($attendance->time_out) {
            throw new Exception('Already clocked out');
        }
        
        $time ??= now();
        
        $attendance->update(['time_out' => $time]);
        
        // Check for overtime
        if (self::isOvertime($employee, $attendance)) {
            // Track for payroll
            $overtimeMinutes = self::getOvertimeMinutes($employee, $attendance);
            HrmsNotification::create([
                'user_id' => $employee->user_id,
                'title' => 'Overtime Recorded',
                'message' => "You worked {$overtimeMinutes} minutes overtime",
                'type' => 'info'
            ]);
        }
        
        return $attendance;
    }
    
    /**
     * Determine if employee is late based on shift
     */
    public static function determineStatus(Employee $employee, Carbon $time): string {
        if (!$employee->shift) {
            // Fallback to default 08:30
            $shiftStart = Carbon::parse('08:30');
        } else {
            $shiftStart = Carbon::parse($employee->shift->start_time);
        }
        
        $gracePeriod = $employee->shift?->grace_period_minutes ?? 15;
        $allowedUntil = $shiftStart->addMinutes($gracePeriod);
        
        return $time->isBefore($allowedUntil) ? 'present' : 'late';
    }
    
    /**
     * Check if hours worked exceed overtime threshold
     */
    public static function isOvertime(Employee $employee, Attendance $attendance): bool {
        if (!$attendance->time_in || !$attendance->time_out) {
            return false;
        }
        
        $hoursWorked = $attendance->time_out->diffInHours($attendance->time_in);
        $threshold = ($employee->shift?->overtime_threshold_minutes ?? 0) / 60;
        
        return $hoursWorked > (8 + $threshold);
    }
    
    /**
     * Get overtime minutes
     */
    public static function getOvertimeMinutes(Employee $employee, Attendance $attendance): int {
        if (!$attendance->time_in || !$attendance->time_out) {
            return 0;
        }
        
        $minutesWorked = $attendance->time_out->diffInMinutes($attendance->time_in);
        $threshold = ($employee->shift?->overtime_threshold_minutes ?? 0);
        
        return max(0, $minutesWorked - (8 * 60 + $threshold));
    }
}
```

---

# Middleware System

## Custom Middleware

### 1. IsAdmin
```php
class IsAdmin {
    public function handle(Request $request, Closure $next) {
        if (!$request->user() || ($request->user()->role !== 'sub_admin' && $request->user()->role !== 'super_admin')) {
            abort(403, 'Unauthorized');
        }
        
        return $next($request);
    }
}
```

### 2. IsEmployee
```php
class IsEmployee {
    public function handle(Request $request, Closure $next) {
        if (!$request->user() || $request->user()->role !== 'employee') {
            abort(403, 'Unauthorized');
        }
        
        return $next($request);
    }
}
```

### 3. SuperAdminOnly
```php
class SuperAdminOnly {
    public function handle(Request $request, Closure $next) {
        if (!$request->user() || $request->user()->role !== 'super_admin') {
            abort(403, 'Only super admins can access this');
        }
        
        return $next($request);
    }
}
```

### 4. CheckIncompleteProfile
```php
class CheckIncompleteProfile {
    public function handle(Request $request, Closure $next) {
        $user = $request->user();
        
        if (!$user) {
            return $next($request);
        }
        
        // Only check for employees
        if ($user->role === 'employee') {
            $employee = $user->employee();
            
            // If no employee record or profile incomplete
            if (!$employee || !$employee->profile_completed) {
                // Allow only setup-related routes
                if (!$request->is('employees/setup', 'employees/setup')) {
                    return redirect()->route('employees.setup');
                }
            }
        }
        
        return $next($request);
    }
}
```

---

# Security & Access Control

## Authorization Checks in Controllers

```php
// Attendance - User can only see own records
$attendance = Attendance::where('employee_id', $employeeId);
if (!$user->isAdmin()) {
    // Limit to own employee record
    $this->authorize('view', $attendance);
}

// Leaves - Similar pattern
$leave = Leave::find($leaveId);
if (!$user->isAdmin()) {
    $this->authorize('view', $leave);
}

// Super Admin - Can manage other admins
Route::middleware('super_admin')->group(function () {
    Route::patch('/users/{id}/assign-admin-role', ...);
});
```

## Data Isolation

```
ADMIN ACCESS:
├─ Can view ALL employees
├─ Can see ALL leaves/timesheets
├─ Can see ALL performance reviews
└─ Can see ALL violations

EMPLOYEE ACCESS:
├─ Can only view OWN records
├─ Cannot see other employees
├─ Cannot see other leave requests
└─ Cannot see other timesheets
```

## Password Security

- Laravel Breeze handles password hashing (bcrypt)
- Password reset via email token
- Email verification required
- CSRF protection on all forms

---

# Development Guide

## Adding a New Feature

### 1. Create Migration
```bash
php artisan make:migration create_new_table_name
```

### 2. Create Model
```bash
php artisan make:model NewModel
```

### 3. Create Controller
```bash
php artisan make:controller NewController
```

### 4. Add Routes (routes/web.php)
```php
Route::middleware('auth')->group(function () {
    Route::resource('new-resource', NewController::class);
});
```

### 5. Create Views (resources/views/new-resource/)
- index.blade.php
- create.blade.php
- edit.blade.php
- show.blade.php

### 6. Run Tests
```bash
php artisan test --filter=NewFeatureTest
```

## Running Commands

```bash
# Start development server
php artisan serve

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Run tests
php artisan test

# Format code with Pint
vendor/bin/pint --dirty

# View logs in real-time
php artisan pail

# Clear cache
php artisan cache:clear

# List all routes
php artisan route:list

# Tinker (debug CLI)
php artisan tinker
```

## Key Configuration Files

- **.env**: Environment variables
- **config/app.php**: App name, timezone, locale
- **config/database.php**: Database connection
- **config/auth.php**: Authentication config
- **bootstrap/app.php**: Middleware & routing
- **bootstrap/providers.php**: Service providers

---

## Summary Statistics

- **14 Database Tables**
- **13 Controllers**
- **14 Models**
- **1 Service Layer** (AttendanceService)
- **4 Custom Middleware**
- **30+ API Routes**
- **10+ User Workflows**
- **3 User Roles** (super_admin, sub_admin, employee)
- **5 Leave Types**
- **5 Violation Levels**
- **4 Performance Ratings**

---

This is your complete HRMS Pro system. Everything is documented, structured, and ready for development!

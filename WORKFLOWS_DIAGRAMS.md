# 🔄 HRMS Pro - System Workflows & Flowcharts

---

## 🎯 System Entry Points

```
┌─────────────────────────────────────────────────────────┐
│                    VISITOR                              │
└─────────────────────────────────────────────────────────┘
                         │
        ┌────────────────┼────────────────┐
        ↓                ↓                ↓
    REGISTER         LOGIN            FORGOT PASSWORD
        │                ↓                │
        │         ✓ Email & Pass          │
        │              │                  │
        │              ↓                  │
        └─────────→ VERIFY EMAIL ←────────┘
                       │
                       ↓
            ┌─────────────────────┐
            │  EMAIL VERIFIED?    │
            └─────────────────────┘
                  YES ↓      ↗ NO
                     │    [RESEND]
                     ↓
            ┌─────────────────────┐
            │  COMPLETE PROFILE   │
            │  (Fill Employee     │
            │   Details)          │
            └─────────────────────┘
                     │
                     ↓
            ┌─────────────────────┐
            │  AWAIT ADMIN        │
            │  APPROVAL           │
            └─────────────────────┘
                     │
            ┌────────┴────────┐
            ↓                 ↓
        APPROVED          REJECTED
            │                 │
            ↓                 │
        ACTIVE              [ASK TO RE-REGISTER]
            │
            ↓
        CAN LOGIN
```

---

## 🕐 Daily Attendance Flow

```
MORNING
┌──────────────────────────────┐
│  Employee visits dashboard   │
│  Sees: "Clock In Available"  │
└──────────────────────────────┘
            │
            ↓
┌──────────────────────────────────────┐
│  Click "⏱ Clock In"                  │
│  System records: time = 08:45        │
└──────────────────────────────────────┘
            │
            ↓
┌──────────────────────────────────────┐
│  Check Shift Times                   │
│  Shift Start: 08:30                  │
│  Grace Period: 15 min                │
│  Allowed until: 08:45                │
└──────────────────────────────────────┘
            │
    ┌───────┴────────┐
    ↓                ↓
IS 08:45   IS 08:45
< 08:45?   > 08:45?
    │                │
    YES              NO
    │                │
    ↓                ↓
STATUS:         STATUS:
'present'       'late'
    │                │
    └────────┬───────┘
             ↓
    ┌──────────────────────────┐
    │  Attendance Created      │
    │  • date: 2026-05-14      │
    │  • time_in: 08:45        │
    │  • status: [present/late]│
    │  • time_out: NULL        │
    └──────────────────────────┘
             │
             ↓
    ┌──────────────────────────┐
    │  Dashboard Updated       │
    │  Shows: Clocked In ✓     │
    └──────────────────────────┘

AFTERNOON
             │
             ↓
    ┌──────────────────────────┐
    │  Employee clicks         │
    │  "⏹ Clock Out"           │
    │  time_out = 17:45        │
    └──────────────────────────┘
             │
             ↓
    ┌──────────────────────────────────┐
    │  Calculate Hours Worked          │
    │  17:45 - 08:45 = 9 hours        │
    │  Overtime: 9 - 8 = 1 hour       │
    └──────────────────────────────────┘
             │
             ↓
    ┌──────────────────────────┐
    │  Check Overtime Threshold│
    │  If 1 hour > threshold:  │
    │  → Notify about OT       │
    └──────────────────────────┘
             │
             ↓
    ┌──────────────────────────┐
    │  Update Attendance       │
    │  • time_out: 17:45       │
    │  • status: present       │
    │  • COMPLETE              │
    └──────────────────────────┘
```

---

## 📅 Leave Request Approval Workflow

```
                    STEP 1: EMPLOYEE REQUEST
                    ┌────────────────────────┐
                    │ Visit /leaves          │
                    │ Fill Form:             │
                    │ • Type: vacation       │
                    │ • Dates: 5/20 - 5/22   │
                    │ • Reason: Family trip  │
                    │ Click: Submit          │
                    └────────────────────────┘
                              │
                              ↓
                    ┌────────────────────────┐
                    │ Leave Record Created   │
                    │ • status: pending      │
                    │ • days: 3              │
                    │ • submitted_at: now()  │
                    └────────────────────────┘
                              │
                              ↓
                    ┌────────────────────────┐
                    │ Notification Sent      │
                    │ "Leave request sent    │
                    │  for admin approval"   │
                    └────────────────────────┘
                              │
                              ↓
                    STEP 2: ADMIN REVIEW
                    ┌────────────────────────┐
                    │ Admin Dashboard shows  │
                    │ "Pending Leaves: 1"    │
                    │ Click to view details  │
                    └────────────────────────┘
                              │
                              ↓
                    ┌────────────────────────┐
                    │ Review Leave Details   │
                    │ • Employee: John Doe   │
                    │ • Dates: 5/20-5/22     │
                    │ • Type: vacation       │
                    │ • Reason shown         │
                    └────────────────────────┘
                              │
                    ┌─────────┴──────────┐
                    ↓                    ↓
                APPROVE              DENY
                    │                    │
                    ↓                    ↓
        ┌────────────────────┐  ┌──────────────────┐
        │ STEP 3: APPROVE    │  │ status: denied   │
        │                    │  │                  │
        │ status: approved   │  │ Notify employee: │
        │ approved_by: 1     │  │ "Leave rejected" │
        │ approved_at: now() │  │                  │
        └────────────────────┘  │ Employee can:    │
        │                       │ • Resubmit       │
        ├─ AUTO-MARK ATTENDANCE │ • Appeal to HR   │
        │  for those dates:     └──────────────────┘
        │                                │
        │  5/20: status='on_leave' ↖     │
        │  5/21: status='on_leave'       │
        │  5/22: status='on_leave'       ↓
        │                       ┌──────────────────┐
        │  ↓                    │ END - PROCESS    │
        │                       │ NOT APPROVED     │
        ├─ CANNOT CLOCK IN      └──────────────────┘
        │  ON THOSE DATES
        │
        ├─ MARK AS PAID TIME
        │  (if paid leave)
        │
        ├─ UPDATE LEAVE BALANCE
        │  (if tracking balance)
        │
        └─ NOTIFY EMPLOYEE
           "Leave approved!
            You're marked
            on leave 5/20-5/22"
           Status: APPROVED ✓
```

---

## 📋 Timesheet Submission Flow

```
END OF WEEK
┌────────────────────────────────┐
│ Employee visits /timesheets    │
│ Sees: "Create Timesheet"       │
└────────────────────────────────┘
            │
            ↓
┌────────────────────────────────┐
│ Fill Timesheet Form:           │
│ • Week: May 12-18, 2026        │
│ • Total Hours: 40.5            │
│ • OT Hours: 2.5                │
│ • Notes: "Covered John Mon"    │
│ Click: Submit                  │
└────────────────────────────────┘
            │
            ↓
┌────────────────────────────────┐
│ Validate Form                  │
│ ✓ Total hours: 0-60 ✓          │
│ ✓ OT hours: 0-20 ✓             │
│ ✓ Notes optional ✓             │
└────────────────────────────────┘
            │
            ↓
┌────────────────────────────────┐
│ Create Timesheet Record        │
│ • employee_id: 2               │
│ • status: pending              │
│ • submitted_at: now()          │
│ • approved_by: NULL            │
└────────────────────────────────┘
            │
            ↓
┌────────────────────────────────┐
│ Dashboard Shows:               │
│ "Timesheet submitted!"         │
└────────────────────────────────┘

┌─────────────────────────────────┐
│ ADMIN REVIEWS                   │
│ Dashboard → "Pending Timesheets"│
│ Sees: 1 pending                 │
└─────────────────────────────────┘
            │
    ┌───────┴────────┐
    ↓                ↓
APPROVE          REJECT
    │                │
    ↓                ↓
┌──────────────┐  ┌──────────────────┐
│ status:      │  │ status: rejected │
│ approved     │  │ reason entered   │
│ approved_by: │  │                  │
│ admin_id     │  │ Notify employee: │
│ ready for    │  │ "Resubmit with   │
│ payroll ✓    │  │  correct hours"  │
│              │  │                  │
│ Notify:      │  │ Employee:        │
│ "Timesheet   │  │ • Can resubmit   │
│  approved!"  │  │ • Can appeal     │
└──────────────┘  └──────────────────┘
```

---

## ⚠️ Violation Escalation Flow

```
FIRST OFFENSE
┌──────────────────────────────┐
│ Admin Records Violation      │
│ • Employee: John             │
│ • Offense: "Late 3 times"    │
│ • Level: "Verbal Warning"    │
│ • offense_count: 1           │
│ • status: open               │
└──────────────────────────────┘
        │
        ↓
┌──────────────────────────────┐
│ Employee Notified            │
│ "Verbal Warning issued"      │
│ Can see in /violations       │
│ Status: OPEN - Can be        │
│         resolved by admin    │
└──────────────────────────────┘

┌──────────────────────────────┐
│ After 3 Months               │
│ No More Issues               │
└──────────────────────────────┘
        │
        ↓
┌──────────────────────────────┐
│ Admin Marks: RESOLVED        │
│ Warning cleared from record  │
│ Count resets                 │
└──────────────────────────────┘

BUT IF REPEATED
┌──────────────────────────────┐
│ SECOND OFFENSE               │
│ Admin Records New Violation  │
│ • Level: "Written Warning"   │
│ • offense_count: 2           │
│ • status: open               │
└──────────────────────────────┘
        │
        ↓
┌──────────────────────────────┐
│ ESCALATION CHAIN:            │
│                              │
│ 1st → Verbal Warning         │
│ 2nd → Written Warning        │
│ 3rd → Final Warning          │
│ 4th → Suspension (no pay)    │
│ 5th → Termination (fired)    │
│                              │
│ Can resolve at any level     │
│ to restart count             │
└──────────────────────────────┘
```

---

## ⭐ Performance Review Flow

```
PERFORMANCE PERIOD ENDS (e.g., End of Q1)
┌────────────────────────────────┐
│ Admin visits /performance      │
│ Clicks "Create Review"         │
└────────────────────────────────┘
            │
            ↓
┌────────────────────────────────┐
│ Fill Review Form:              │
│ • Employee: Jane Smith         │
│ • Period: Q1 2026              │
│ • Score: 8.5 (out of 10)       │
│ • Feedback:                    │
│   "Great teamwork, consistent  │
│    high-quality deliverables"  │
│ Click: Submit                  │
└────────────────────────────────┘
            │
            ↓
┌────────────────────────────────┐
│ Score Auto-Rated:              │
│ 8.5 → 'Satisfactory'           │
│                                │
│ Rating Scale:                  │
│ 9-10: Outstanding              │
│ 7-8: Satisfactory ✓ (8.5)      │
│ 5-6: Needs Improvement         │
│ <5: Poor                       │
└────────────────────────────────┘
            │
            ↓
┌────────────────────────────────┐
│ Review Created:                │
│ • reviewed_by: admin_id        │
│ • created_at: now()            │
└────────────────────────────────┘
            │
            ↓
┌────────────────────────────────┐
│ Employee Notified              │
│ Clicks /performance/my         │
│ Sees review details            │
│ Can download/print             │
└────────────────────────────────┘
```

---

## 👤 Admin Role Assignment Flow (Super Admin Only)

```
SUPER ADMIN
┌─────────────────────────────────┐
│ Visits: /users/{id}/make-admin  │
│ Shows: Employee John Doe        │
│ Currently: employee role        │
└─────────────────────────────────┘
            │
            ↓
┌─────────────────────────────────┐
│ Select Role:                    │
│ ⊙ sub_admin (HR Manager)        │
│ ○ super_admin (Super Admin)     │
│ Click: "Assign Role"            │
└─────────────────────────────────┘
            │
            ↓
┌─────────────────────────────────┐
│ Confirmation Dialog             │
│ "Assign sub_admin role to       │
│  John Doe?                      │
│                                 │
│  This will grant admin          │
│  privileges. Proceed?"          │
│  [CANCEL] [CONFIRM]             │
└─────────────────────────────────┘
            │
            ↓
┌─────────────────────────────────┐
│ Role Updated                    │
│ John's role: employee → sub_admin
│ Redirected to success page      │
└─────────────────────────────────┘
            │
            ↓
┌─────────────────────────────────┐
│ John's NEW PERMISSIONS:         │
│ ✓ Access /employees             │
│ ✓ Approve leaves                │
│ ✓ Approve timesheets            │
│ ✓ Manage violations             │
│ ✓ Write performance reviews     │
│ ✓ View admin dashboard          │
│ ✓ See /requests queue           │
│ ✗ Cannot manage other admins    │
└─────────────────────────────────┘
            │
            ↓
┌─────────────────────────────────┐
│ John Receives Notification      │
│ "You've been promoted to        │
│  Sub-Admin"                     │
│                                 │
│ Next login will show admin      │
│ dashboard                       │
└─────────────────────────────────┘
```

---

## 📝 User Request (Account Activation) Flow

```
USER REGISTRATION
┌──────────────────────────────┐
│ 1. User registers            │
│ 2. Email verified            │
│ 3. Profile completed         │
│ 4. Waiting...                │
└──────────────────────────────┘
            │
            ↓
┌──────────────────────────────┐
│ UserRequest Created:         │
│ • type: "Account Activation" │
│ • status: pending            │
│ • user_id: 5                 │
│ • created_at: now()          │
└──────────────────────────────┘
            │
            ↓
┌──────────────────────────────┐
│ User Redirected to:          │
│ /pending page                │
│ "Awaiting admin approval"    │
│                              │
│ Cannot login yet             │
│ User status: 'pending'       │
└──────────────────────────────┘
            │
            ↓
┌──────────────────────────────┐
│ ADMIN WORKFLOW               │
│ Dashboard → Requests         │
│ Shows: 1 Pending Request     │
│ Type: Account Activation     │
│ User: John Doe               │
│ [APPROVE] [REJECT]           │
└──────────────────────────────┘
            │
    ┌───────┴────────┐
    ↓                ↓
APPROVE          REJECT
    │                │
    ↓                ↓
┌──────────────┐  ┌──────────────────┐
│ UserRequest: │  │ UserRequest:     │
│ status:      │  │ status: rejected │
│ approved     │  │                  │
│              │  │ User status:     │
│ User status: │  │ 'rejected'       │
│ changed to   │  │                  │
│ 'active'     │  │ Notify: "Your    │
│              │  │ account was not  │
│ Notify:      │  │ approved. You    │
│ "Account     │  │ may reapply"     │
│ approved!"   │  │                  │
│              │  │ Can try to       │
│ User can now │  │ register again   │
│ LOGIN ✓      │  │                  │
│              │  │                  │
│ Dashboard    │  │ Status: REJECTED │
│ available    │  └──────────────────┘
└──────────────┘
```

---

## 🔐 Authentication & Authorization Flow

```
┌─────────────────────────────┐
│ UNAUTHENTICATED USER        │
│ Visits: /dashboard          │
└─────────────────────────────┘
            │
            ↓
┌─────────────────────────────┐
│ auth middleware             │
│ Check: User logged in?      │
│ Result: NO                  │
└─────────────────────────────┘
            │
            ↓
┌─────────────────────────────┐
│ Redirect to /login          │
│ Display login form          │
└─────────────────────────────┘

┌─────────────────────────────┐
│ AUTHENTICATED USER          │
│ Visits: /employees          │
└─────────────────────────────┘
            │
            ↓
┌─────────────────────────────┐
│ auth middleware             │
│ Check: User logged in?      │
│ Result: YES (User found)    │
└─────────────────────────────┘
            │
            ↓
┌─────────────────────────────┐
│ admin middleware            │
│ Check: Role in             │
│ ['sub_admin', 'super_admin']?
└─────────────────────────────┘
            │
    ┌───────┴────────┐
    ↓                ↓
   YES              NO
    │                │
    ↓                ↓
GRANT          ABORT 403
ACCESS        "Unauthorized"
    │                │
    ↓                ↓
SHOW          ERROR PAGE
/employees

┌─────────────────────────────┐
│ CheckIncompleteProfile      │
│ (Applied to ALL web routes) │
│                             │
│ If employee role:           │
│   Check: profile_completed? │
│   If NO → redirect to setup │
└─────────────────────────────┘
```

---

## 📊 Data Flow: Time-In to Dashboard

```
EMPLOYEE CLICKS CLOCK IN
        │
        ↓
    POST /attendance/time-in
        │
        ↓
    AttendanceController::timeIn()
        │
        ├─ Get current employee
        │
        ├─ Call AttendanceService::recordTimeIn($employee)
        │  │
        │  ├─ Check: Already clocked in today?
        │  │
        │  ├─ Create Attendance record
        │  │
        │  └─ Determine Status based on shift:
        │     ├─ Get shift start time
        │     ├─ Add grace period
        │     ├─ Compare to current time
        │     └─ Set status: 'present' or 'late'
        │
        ├─ Create notification (if needed)
        │
        └─ Redirect with success message
        
        ↓
    DATABASE UPDATED
    attendances table:
    {
      id: 123
      employee_id: 5
      date: 2026-05-14
      time_in: 2026-05-14 08:45:00
      status: 'present'
      created_at: now()
    }
        │
        ↓
    DASHBOARD REFRESH
    Employee sees:
    ✓ "Clocked in at 08:45"
    ✓ Status: PRESENT
    ✓ Clock Out button visible
        │
        ↓
    END OF DAY
    Employee clicks CLOCK OUT
        │
        ↓
    Attendance record updated:
    {
      time_out: 2026-05-14 17:45:00
      hoursWorked: 9 hours
      overtimeMinutes: 60 (1 hour)
    }
```

---

## 🔄 Data Relationships Diagram

```
USER
├─ 1:1 → EMPLOYEE
│        ├─ 1:N → ATTENDANCE (daily records)
│        ├─ 1:N → LEAVE (requests)
│        ├─ 1:N → TIMESHEET (weekly)
│        ├─ 1:N → VIOLATION (disciplinary)
│        ├─ 1:N → PERFORMANCE (reviews)
│        └─ 1:N → ASSIGNED_TIMESHEET (tasks)
│
├─ 1:N → HRMS_NOTIFICATION (messages sent to user)
├─ 1:N → USER_REQUEST (pending approvals)
├─ 1:N → LEAVE (as approver: approved_by)
├─ 1:N → TIMESHEET (as approver: approved_by)
├─ 1:N → VIOLATION (as issuer: issued_by)
├─ 1:N → PERFORMANCE (as reviewer: reviewed_by)
└─ 1:N → AUDIT_LOG (as admin making changes)

EMPLOYEE
├─ N:1 → SHIFT
├─ N:1 → USER
└─ All records linked by employee_id

SHIFT
└─ 1:N → EMPLOYEE (employees assigned to shift)
```

---

**Visual Legend**:
- `→` = Direct relationship
- `├─` = Multiple items
- `↓` = Process flow down
- `┌─ ` = Box/decision point
- `[ ]` = Button/action
- `✓` = Success state
- `✗` = Blocked/failed state


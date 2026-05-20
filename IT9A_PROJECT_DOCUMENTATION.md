# IT9A PROJECT DOCUMENTATION
## Human Resources Management System (HrMs)

---

## 1. COVER PAGE

**Project Title:** Human Resources Management System (HrMs)

**Course / Subject:** IT9A - Systems Development / Software Engineering Project

**Instructor:** [Insert Instructor Name]

**Student Name(s):** [Student Names]

**Section:** [Section Number]

**Date Submitted:** [Submission Date]

**System Overview:**
- Web-based HR management platform built with Laravel 12
- Streamlined employee lifecycle management from onboarding to performance tracking
- Real-time attendance tracking with time-in/time-out functionality
- Role-based access control with Super Admin, Sub Admin, and Employee roles

---

## 2. BUSINESS CASE

### 2.1 Background of the Study

#### What is happening now?

Many organizations, particularly small to medium-sized enterprises in the Philippines, continue to rely on fragmented, manual methods to manage their Human Resources operations. Employee records are scattered across multiple spreadsheets and paper files with no centralized repository. Attendance tracking is performed manually through logbooks or spreadsheets that are updated by hand each day, often with inconsistencies and data entry errors. Leave requests are submitted via email or paper forms with no standardized approval workflow, causing delays and conflicts when multiple requests overlap. Performance evaluations are sporadic and poorly documented, making it impossible to track employee growth or make data-driven decisions about promotions or compensation. Timesheet submissions are recorded on paper or in disconnected spreadsheets with no systematic approval process. Compliance and audit trails are minimal, creating regulatory and security risks. These fragmented, manual processes create significant inefficiencies, compliance risks, and limit HR's ability to focus on strategic initiatives rather than administrative tasks.

#### Who are affected?

The problems caused by manual HR operations directly affect multiple stakeholders within the organization.

**Human Resources Department:** HR personnel are overwhelmed with repetitive administrative tasks—manually entering employee data, processing leave requests, calculating attendance records, and organizing performance reviews. This leaves little time for strategic HR initiatives like talent development and employee engagement.

**Employees:** Employees struggle with slow leave approval processes, lack visibility into their attendance records and leave balances, and have no centralized way to track their performance feedback or career development. They also face delays in onboarding and lack clarity on their employment details.

**Management & Leadership:** Managers lack real-time visibility into team attendance, leave status, and performance metrics. They cannot quickly identify staffing issues, attendance problems, or performance trends. Decision-making about team composition, promotions, and compensation is based on incomplete or outdated information.

**Organization as a Whole:** The company faces compliance risks due to poor record-keeping and audit trails. Data inconsistencies create potential legal liability. The inability to track and analyze HR metrics prevents data-driven business decisions and limits organizational efficiency.

#### Why is a system needed?

A centralized, digital HR management system is essential to replace these inefficient manual processes with a reliable, automated, and real-time platform. Without a system, the risks are significant: critical HR data is lost or inaccessible, compliance violations go undetected, employees experience poor service, management operates with incomplete information, and HR staff cannot focus on strategic initiatives. A properly designed HR system can enforce standardized workflows automatically, maintain accurate records through centralized databases, provide real-time visibility into organizational metrics, and enable data-driven decision-making—all of which are impossible to achieve consistently through manual methods alone.

### 2.2 Problem Statement

The following specific problems have been identified in the current manual HR management process:

1. **Fragmented and Inaccessible Employee Records:** Employee information is scattered across multiple spreadsheets, paper files, and folders with no centralized storage. Data retrieval is slow and error-prone. When an employee changes departments or updates personal information, the new data may not propagate across all records, creating inconsistencies. Critical compliance information (government IDs, contract dates) is often missing or outdated.

2. **Manual and Inaccurate Attendance Tracking:** Daily attendance is recorded manually in logbooks or spreadsheets, which are updated by hand and prone to human error. Discrepancies frequently arise between actual attendance and recorded data. Managers cannot quickly verify who is present, absent, or late. Historical attendance data is difficult to retrieve and analyze, making it impossible to identify patterns or address chronic absenteeism.

3. **Inefficient Leave Management Without Standardized Workflow:** Leave requests are submitted via email or paper with no systematic tracking of approval status. Multiple requests for overlapping dates can conflict. Leave balances are tracked manually and often incorrectly calculated. Employees do not know their remaining leave days, and managers cannot see pending requests or plan for team coverage. The absence of a clear workflow causes delays and frustration for both employees and management.

4. **Lack of Performance Tracking and Documentation:** There is no centralized system to record, track, or analyze employee performance reviews over time. Performance data is stored in scattered documents (if at all), making it impossible to identify trends, assess employee development, or make fair decisions about compensation and promotions. Employees receive little feedback on their performance and have no visibility into how they are being evaluated.

5. **Absence of Compliance and Audit Trails:** The organization cannot systematically track who accessed what information and when, creating compliance and security risks. HR changes (new hires, terminations, role changes) are not logged. In the event of a dispute or audit, there is no reliable record of decisions or actions taken. This exposes the organization to legal liability.

6. **No Real-Time Visibility for Management:** Managers and executives have no dashboard or report showing real-time organizational metrics such as attendance rates, leave usage, pending approvals, or performance trends. Strategic decisions about staffing, resource allocation, and compensation are made without complete information. The organization cannot respond quickly to staffing issues or operational bottlenecks.

### 2.3 Objectives of the System

#### General Objective:

To develop a secure, role-based, and fully automated web-based Human Resources Management System using the Laravel 12 framework and MySQL that digitalizes the core operations of an organization's HR department, including employee management, attendance tracking, leave management, performance evaluation, and compliance logging, in order to eliminate manual inefficiencies, enforce regulatory compliance, provide real-time operational visibility, and enable HR to focus on strategic initiatives rather than administrative tasks.

#### Specific Objectives:

- **SO1:** Implement a centralized employee record management system that maintains complete and accurate employee profiles including personal information, employment details, government IDs, and contract information in a single, secure database.

- **SO2:** Develop an automated real-time attendance tracking system with clock in/out functionality, shift management, and automatic hours calculation that eliminates manual logbooks and provides accurate attendance records.

- **SO3:** Create a standardized leave request and approval workflow with automated notifications, leave balance tracking, and conflict detection to streamline leave management and improve employee satisfaction.

- **SO4:** Build a performance management module that systematically records, tracks, and analyzes employee performance reviews and evaluations over time to support data-driven HR decisions.

- **SO5:** Implement a three-tier role-based access control system (Super Admin, Sub Admin, Employee) with middleware-enforced authorization that ensures users can only access functions and data relevant to their role.

- **SO6:** Develop role-specific dashboards that display real-time operational KPIs including attendance metrics, leave status, pending approvals, and performance trends to enable informed decision-making.

- **SO7:** Establish comprehensive audit logging and compliance tracking that records all system actions, user changes, and data modifications to ensure accountability and regulatory compliance.

- **SO8:** Create a timesheet management system that allows employees to submit weekly work records and enables administrators to review, approve, or reject submissions with documented reasons.

- **SO9:** Implement a violation and discipline tracking module to systematically record and monitor employee infractions and their resolution.

- **SO10:** Develop a real-time notification system that alerts users to pending approvals, actions requiring attention, and system updates.

### 2.4 Proposed Solution

#### What will it do?

HrMs is a web-based Human Resources Management System that automates and centralizes the core daily operations of an organization's HR department. The system digitally manages employee records in a centralized database, maintaining complete and up-to-date information accessible to authorized users. It automates attendance tracking through a real-time clock in/out module that calculates hours worked automatically and tracks multiple attendance statuses (present, late, absent, half day, on leave). It streamlines leave management by automating the request submission, approval, and notification workflow while preventing scheduling conflicts. It tracks employee performance through a structured review system that maintains historical evaluation data. It manages employee timesheets through a submission and approval workflow. It enforces role-based access control, ensuring each user only has access to appropriate functions and data. It maintains comprehensive audit logs of all system activities for compliance and accountability. In summary, HrMs replaces all manual, paper-based HR workflows with a secure, automated, and real-time digital system that improves efficiency, accuracy, and strategic capability.

#### Who will use it?

**Super Admin:** The Super Admin is the highest-level user who manages system configuration, approves new staff account registrations and role assignments, maintains system-wide settings, views comprehensive reports and analytics, and has full read/write access across all modules. The Super Admin can promote other users to Sub Admin status and manage system security.

**Sub Admin:** The Sub Admin manages core HR operations including employee records, leave approvals, timesheet approvals, and performance management. The Sub Admin can view dashboards with department-level analytics and generate operational reports. The Sub Admin serves as the day-to-day HR operational manager but does not have access to system configuration or user role management.

**Employees:** Employees access the system to view their personal information, clock in and out for attendance, submit leave requests, view their leave balance and history, submit timesheets, view their performance reviews, track notifications, and manage their profile. Employees have no access to other employees' data or administrative functions.

#### What are its main features?

**Authentication & User Account Management:**
- Email-based secure login with password hashing
- Three-tier role system (Super Admin, Sub Admin, Employee) with explicit approval workflow for all new accounts
- Account status management (Active, Pending, Inactive) with immediate lockout for deactivated accounts
- Email verification for new registrations

**Centralized Employee Management:**
- Complete employee profile management with personal, employment, and compliance information
- Employee onboarding workflow with profile completion tracking
- Department and position tracking
- Government ID integration (SSS, PagIBIG, PhilHealth numbers)
- Employment contract tracking (date hired, contract expiry)
- Soft-delete capability with restore option for terminated employees
- Batch employee operations (deactivate, export) for administrative efficiency

**Real-Time Attendance Tracking:**
- Clock in/out functionality with time validation (prevents future times)
- Live clock display for employees showing current time and shift information
- Automatic hours worked calculation
- Shift-based attendance validation
- Multiple attendance status options (Present, Late, Absent, Half Day, On Leave)
- Attendance record editing capability for administrators
- Comprehensive attendance history with filtering by date, employee, department, and status
- Admin dashboard showing daily attendance KPIs (present count, late arrivals, absences, on leave)

**Shift Management:**
- Create and manage work shifts with defined start and end times
- Assign shifts to employees
- Shift information display during attendance tracking for verification

**Leave Management:**
- Employee leave request submission with multiple leave types
- Automatic leave balance calculation and tracking
- Approval workflow with administrator notifications
- Leave denial with reason documentation
- Leave history viewing for employees and administrators
- Admin dashboard showing pending leave approvals
- Leave analytics and reporting

**Timesheet Management:**
- Weekly timesheet creation and submission by employees
- Task tracking per day with detailed descriptions
- Automatic total hours calculation
- Timesheet approval/rejection workflow for administrators
- Rejection reasons documentation for transparency
- Batch timesheet assignment to multiple employees
- Timesheet history and status tracking
- Personal timesheet dashboard ("My Timesheets")

**Performance Management:**
- Create and record performance reviews with multiple evaluation dimensions
- 5-star rating system with detailed feedback capability
- Review period tracking (quarterly, annual)
- Performance history display for trend analysis
- Employee access to their own performance reviews
- Performance analytics for management

**Violation & Discipline Tracking:**
- Record employee violations with type, date, and detailed description
- Track violation status and resolution
- Maintain disciplinary records for compliance
- Employee violation history viewable by authorized personnel

**Notifications System:**
- Real-time in-app notifications for critical updates
- Multiple notification types (leave approvals/denials, timesheet status, administrative actions)
- Mark notifications as read functionality
- Notification history for reference
- Read-all action for bulk marking
- User-specific notification inbox

**Role-Based Access Control:**
- Middleware-enforced authorization at route level
- Permission-based system with role-to-permission mapping
- Dynamic permission checking during request processing
- Resource-level access control (employees can only view their own data)
- Super Admin override capabilities

**Comprehensive Audit & Compliance:**
- Complete audit logging of all user actions
- Track model changes (old values vs new values)
- IP address logging for security
- Timestamp recording for all activities
- Compliance-ready audit trail for regulatory requirements

**Role-Specific Dashboards:**
- **Admin Dashboard:** System-wide metrics, KPIs, pending approvals, recent activities
- **Employee Dashboard:** Personal attendance status, leave balance, recent records, notifications, quick access to key functions

### 2.5 Scope and Limitations

#### Scope

The HrMs system provides a comprehensive employee management solution featuring a secure multi-step user registration process that includes email verification and role assignment, managed through an administrator-controlled approval workflow. Security is bolstered by role-based access control for Super Admin, Sub Admin, and Employee roles, which is strictly enforced at the route middleware level. The system offers robust employee management capabilities, including complete employee profiles with personal, employment, and compliance information, alongside department and position tracking. Attendance features include real-time clock in/out functionality with automatic hours calculation, shift-based validation, and multiple attendance status options. Leave management includes a standardized request and approval workflow with notifications and leave balance tracking. Timesheet management features weekly submission, approval workflow, and batch assignment capabilities. Performance management enables systematic review recording with ratings and feedback. The system includes violation tracking for disciplinary records, a real-time notification system, role-specific dashboards with operational KPIs, comprehensive audit logging, and employee onboarding workflows. All features are built with Laravel 12, MySQL database, responsive web interface, and role-based access control.

#### Limitations

The system's current scope excludes several advanced features and integrations to maintain focus on core HR operations. Specifically, it does not support:
- Payroll processing and salary calculations
- Benefits and allowance management
- Recruitment and applicant tracking
- Training and development module
- Native mobile applications (iOS/Android) — web is responsive but not native mobile
- Email or SMS automated notifications (basic in-app notifications only)
- Multi-branch or multi-company management
- Advanced business intelligence and forecasting
- Integration with external payroll systems
- Integration with health insurance systems (PhilHealth, HMO)
- Biometric attendance integration or hardware device support
- API integrations with third-party systems
- Advanced analytics and predictive reporting

---

## 3. ENTITY-RELATIONSHIP DIAGRAM (ERD)

### ERD Visual Representation:

```
┌─────────────────────────────┐                    ┌──────────────────────────────┐
│         USERS               │ 1:1 Relationship   │       EMPLOYEES              │
├─────────────────────────────┤◄──────────────────►├──────────────────────────────┤
│ PK: id                      │                    │ PK: id                       │
│ name                        │                    │ FK: user_id (UNIQUE)         │
│ email (UNIQUE)              │                    │ employee_id (UNIQUE)         │
│ password                    │                    │ first_name                   │
│ role                        │                    │ last_name                    │
│ status                      │                    │ email                        │
│ email_verified_at           │                    │ phone                        │
│ created_at, updated_at      │                    │ address                      │
└─────────────────────────────┘                    │ department                   │
                                                    │ position                     │
                                                    │ date_hired                   │
                                                    │ date_of_birth                │
                                                    │ contract_expiry              │
                                                    │ status                       │
                                                    │ sss_number, pagibig_number   │
                                                    │ philhealth_number            │
                                                    │ FK: shift_id                 │
                                                    │ profile_completed            │
                                                    │ deleted_at, created_at       │
                                                    └──────────────────────────────┘
                                                            │
                        ┌───────────────────┬────────────────┼────────────────┬──────────────────┐
                        │ 1:M               │ 1:M            │ 1:M            │ 1:M              │ M:1
                        │                   │                │                │                  │
            ┌─────────────────────┐ ┌──────────────────┐ ┌──────────────────┐ ┌─────────────┐ ┌────────────────┐
            │   ATTENDANCE        │ │     LEAVES       │ │   TIMESHEETS     │ │PERFORMANCE  │ │  SHIFTS        │
            ├─────────────────────┤ ├──────────────────┤ ├──────────────────┤ ├─────────────┤ ├────────────────┤
            │ PK: id              │ │ PK: id           │ │ PK: id           │ │ PK: id      │ │ PK: id         │
            │ FK: employee_id     │ │ FK: employee_id  │ │ FK: employee_id  │ │FK: employee_id  │ name (UNIQUE)  │
            │ date                │ │ type             │ │ week_start       │ │ rating      │ │ start_time     │
            │ time_in             │ │ start_date       │ │ week_end         │ │ comments    │ │ end_time       │
            │ time_out            │ │ end_date         │ │ total_hours      │ │ review_date │ │ created_at     │
            │ status              │ │ days             │ │ status           │ │ review_     │ │ updated_at     │
            │ notes               │ │ reason           │ │ rejection_reason │ │ period      │ └────────────────┘
            │ created_at          │ │ status           │ │ FK: approved_by  │ │FK: reviewed_by
            │ updated_at          │ │ FK: approved_by  │ │ FK: assigned_     │ │ created_at
            └─────────────────────┘ │ approved_at      │ │    timesheet_id  │ │ updated_at
                                    │ created_at       │ │ created_at       │ └─────────────┘
                                    │ updated_at       │ │ updated_at       │
                                    └──────────────────┘ └──────────────────┘
                                            │                       │
                                            │ M:1 (Approver)       │
                                            │                       │ 1:M
                                            │                       │
                                            └──────┬───────────────┘
                                                   │
                                            ┌──────────────────────┐
                                            │ ASSIGNED_TIMESHEETS  │
                                            ├──────────────────────┤
                                            │ PK: id               │
                                            │ FK: employee_id      │
                                            │ week_start           │
                                            │ week_end             │
                                            │ status               │
                                            │ created_at           │
                                            │ updated_at           │
                                            └──────────────────────┘

    From USERS (1:M)          From EMPLOYEES (1:M)
            │                         │
    ┌───────────────────────┐   ┌─────────────────┐
    │  HRMS_NOTIFICATIONS   │   │   VIOLATIONS    │
    ├───────────────────────┤   ├─────────────────┤
    │ PK: id                │   │ PK: id          │
    │ FK: user_id           │   │ FK: employee_id │
    │ type                  │   │ violation_type  │
    │ title                 │   │ description     │
    │ message               │   │ violation_date  │
    │ is_read               │   │ status          │
    │ created_at            │   │ created_at      │
    │ updated_at            │   │ updated_at      │
    └───────────────────────┘   └─────────────────┘

    ┌──────────────────────┐      ┌─────────────────────┐
    │    AUDIT_LOGS        │      │   USER_REQUESTS     │
    ├──────────────────────┤      ├─────────────────────┤
    │ PK: id               │      │ PK: id              │
    │ FK: user_id          │      │ FK: user_id         │
    │ action               │      │ type                │
    │ model                │      │ status              │
    │ model_id             │      │ created_at          │
    │ old_values (JSON)    │      │ updated_at          │
    │ new_values (JSON)    │      └─────────────────────┘
    │ ip_address           │
    │ created_at           │      ┌────────────────────────┐
    └──────────────────────┘      │ ROLE_PERMISSIONS      │
                                  ├────────────────────────┤
                                  │ PK: id                 │
                                  │ role                   │
                                  │ FK: permission_id      │
                                  │ created_at             │
                                  └────────────────────────┘
                                           │
                                           │ M:1
                                           │
                                  ┌────────────────────────┐
                                  │  PERMISSIONS           │
                                  ├────────────────────────┤
                                  │ PK: id                 │
                                  │ name (UNIQUE)          │
                                  │ description            │
                                  │ created_at             │
                                  └────────────────────────┘
```

### Detailed Relationship Explanations:

**1. Users ↔ Employees (1:1)**
Each user account (login credential) is linked to exactly one employee record (HR data). An employee can only have one user account, and each user must correspond to an employee. This relationship establishes the foundation for role-based access control and ensures every authenticated user has associated employee information.

**2. Employees → Attendance (1:M)**
One employee has many attendance records, with one record created for each working day. The employee_id foreign key links each attendance entry to the specific employee who clocked in/out. This allows the system to maintain a complete attendance history for each employee over time.

**3. Employees → Leaves (1:M)**
One employee can submit multiple leave requests over time. Each leave request is associated with a single employee through the employee_id foreign key. This relationship enables leave tracking, balance management, and approval workflows on a per-employee basis.

**4. Employees → Timesheets (1:M)**
One employee can create and submit multiple timesheets (typically weekly). Each timesheet record is linked to an employee through the employee_id foreign key. This allows employees to maintain a history of submitted timesheets and enables management to review and approve work hours.

**5. Employees → Performance (1:M)**
One employee can receive multiple performance reviews over their tenure. Each performance review record is associated with a single employee and includes rating, feedback, and review date. This enables tracking of employee performance trends and historical evaluation records.

**6. Employees → Violations (1:M)**
One employee can have multiple violation or disciplinary records. Each violation is linked to an employee through the employee_id foreign key and documents infractions with type, date, and resolution status. This maintains a disciplinary history for compliance and HR decision-making.

**7. Employees → Shift (M:1)**
Many employees can be assigned to the same work shift, but each employee is assigned to only one shift. The shift_id foreign key in the Employees table references the Shifts table. This allows flexible shift scheduling where multiple employees share the same shift schedule.

**8. Leaves → Users (M:1 - Approver)**
Multiple leave requests can be approved by the same user (typically an admin/manager), but each leave is approved by one user. The approved_by foreign key in the Leaves table references the Users table. This tracks who approved each leave request for accountability.

**9. Timesheets → Users (M:1 - Approver)**
Multiple timesheets can be approved by the same user (typically an admin/manager), but each timesheet is approved by one user. The approved_by foreign key tracks approval accountability. This enables administrators to review and approve employee timesheets.

**10. Performance → Users (M:1 - Reviewer)**
Multiple performance reviews can be conducted by the same user (typically a manager), but each review is written by one reviewer. The reviewed_by foreign key links performance reviews to the user who conducted them. This maintains reviewer accountability and enables filtering of reviews by reviewer.

**11. Timesheets → AssignedTimesheets (1:M)**
One assigned timesheet batch can be linked to multiple individual timesheet submissions. The assigned_timesheet_id foreign key links individual timesheets to their batch assignment. This enables bulk assignment of timesheet periods to employees.

**12. Users → HrmsNotifications (1:M)**
One user can receive multiple notifications over time. The user_id foreign key links each notification to its recipient. This enables real-time notification delivery for approvals, alerts, and system updates.

**13. Users → AuditLogs (1:M)**
One user's actions generate multiple audit log entries. The user_id foreign key tracks who performed each action. This maintains a complete audit trail for compliance, security, and accountability purposes.

**14. Users → UserRequests (1:M)**
One user can have multiple pending account requests or status change requests. The user_id foreign key links requests to the user. This tracks user account lifecycle events like new account approvals.

**15. RolePermissions ↔ Permissions (M:1)**
Multiple role-permission mappings can reference the same permission, but each mapping refers to one permission. The permission_id foreign key enables flexible permission assignment to roles. This allows reuse of permissions across different roles.

### Key Design Principles:

- **Primary Keys (PK):** All entities use auto-incrementing BIGINT `id` as primary key for scalability
- **Foreign Keys (FK):** All relationships use explicit foreign key constraints for referential integrity
- **Soft Deletes:** Employees table includes `deleted_at` for soft-deletes to preserve historical data
- **Timestamps:** All entities include `created_at` and `updated_at` for audit trail
- **Status Fields:** Various entities include status fields (active, pending, approved, etc.) for workflow management
- **Approver Tracking:** Leave, Timesheet, and Performance records track who approved/reviewed them

---

## 4. DATA DICTIONARY

### Table 1: Users
| Field Name | Data Type | Description | Constraints |
|-----------|-----------|-------------|------------|
| id | BIGINT | Primary Key | PK, Auto Increment |
| name | VARCHAR(255) | User full name | NOT NULL |
| email | VARCHAR(255) | User email address | UNIQUE, NOT NULL |
| email_verified_at | TIMESTAMP | Email verification timestamp | NULLABLE |
| password | VARCHAR(255) | Hashed password | NOT NULL |
| role | VARCHAR(50) | User role (super_admin, sub_admin, employee) | NOT NULL, Default: 'employee' |
| status | VARCHAR(50) | Account status (active, pending, inactive) | NOT NULL, Default: 'pending' |
| remember_token | VARCHAR(100) | Password reminder token | NULLABLE |
| created_at | TIMESTAMP | Record creation timestamp | DEFAULT CURRENT_TIMESTAMP |
| updated_at | TIMESTAMP | Last update timestamp | DEFAULT CURRENT_TIMESTAMP |

### Table 2: Employees
| Field Name | Data Type | Description | Constraints |
|-----------|-----------|-------------|------------|
| id | BIGINT | Primary Key | PK, Auto Increment |
| user_id | BIGINT | Foreign Key to Users | FK, NOT NULL, UNIQUE |
| employee_id | VARCHAR(50) | Employee ID number | UNIQUE, NOT NULL |
| first_name | VARCHAR(100) | Employee first name | NOT NULL |
| last_name | VARCHAR(100) | Employee last name | NOT NULL |
| email | VARCHAR(255) | Employee email | NOT NULL |
| phone | VARCHAR(20) | Contact phone number | NULLABLE |
| address | TEXT | Home address | NULLABLE |
| department | VARCHAR(100) | Department name | NULLABLE |
| position | VARCHAR(100) | Job position | NULLABLE |
| date_hired | DATE | Hiring date | NULLABLE |
| date_of_birth | DATE | Date of birth | NULLABLE |
| contract_expiry | DATE | Employment contract end date | NULLABLE |
| status | VARCHAR(50) | Employment status (active, inactive) | NOT NULL, Default: 'active' |
| sss_number | VARCHAR(50) | Social Security System number | NULLABLE |
| pagibig_number | VARCHAR(50) | PagIBIG number | NULLABLE |
| philhealth_number | VARCHAR(50) | PhilHealth number | NULLABLE |
| shift_id | BIGINT | Foreign Key to Shifts | FK, NULLABLE |
| profile_completed | BOOLEAN | Profile completion flag | Default: false |
| deleted_at | TIMESTAMP | Soft delete timestamp | NULLABLE |
| created_at | TIMESTAMP | Record creation timestamp | DEFAULT CURRENT_TIMESTAMP |
| updated_at | TIMESTAMP | Last update timestamp | DEFAULT CURRENT_TIMESTAMP |

### Table 3: Attendance
| Field Name | Data Type | Description | Constraints |
|-----------|-----------|-------------|------------|
| id | BIGINT | Primary Key | PK, Auto Increment |
| employee_id | BIGINT | Foreign Key to Employees | FK, NOT NULL |
| date | DATE | Attendance date | NOT NULL |
| time_in | TIMESTAMP | Clock-in time | NULLABLE |
| time_out | TIMESTAMP | Clock-out time | NULLABLE |
| status | VARCHAR(50) | Status (present, late, absent, half_day, on_leave) | NOT NULL |
| notes | TEXT | Additional notes | NULLABLE |
| created_at | TIMESTAMP | Record creation timestamp | DEFAULT CURRENT_TIMESTAMP |
| updated_at | TIMESTAMP | Last update timestamp | DEFAULT CURRENT_TIMESTAMP |

### Table 4: Leaves
| Field Name | Data Type | Description | Constraints |
|-----------|-----------|-------------|------------|
| id | BIGINT | Primary Key | PK, Auto Increment |
| employee_id | BIGINT | Foreign Key to Employees | FK, NOT NULL |
| type | VARCHAR(50) | Leave type (vacation, sick, personal, etc.) | NOT NULL |
| start_date | DATE | Leave start date | NOT NULL |
| end_date | DATE | Leave end date | NOT NULL |
| days | INT | Number of leave days | NOT NULL |
| reason | TEXT | Leave reason/description | NULLABLE |
| status | VARCHAR(50) | Status (pending, approved, denied) | NOT NULL, Default: 'pending' |
| approved_by | BIGINT | Foreign Key to Users (approver) | FK, NULLABLE |
| approved_at | TIMESTAMP | Approval timestamp | NULLABLE |
| created_at | TIMESTAMP | Record creation timestamp | DEFAULT CURRENT_TIMESTAMP |
| updated_at | TIMESTAMP | Last update timestamp | DEFAULT CURRENT_TIMESTAMP |

### Table 5: Timesheets
| Field Name | Data Type | Description | Constraints |
|-----------|-----------|-------------|------------|
| id | BIGINT | Primary Key | PK, Auto Increment |
| employee_id | BIGINT | Foreign Key to Employees | FK, NOT NULL |
| week_start | DATE | Week starting date | NOT NULL |
| week_end | DATE | Week ending date | NOT NULL |
| total_hours | DECIMAL(10,2) | Total hours worked in week | NULLABLE |
| status | VARCHAR(50) | Status (pending, approved, rejected) | NOT NULL, Default: 'pending' |
| rejection_reason | TEXT | Reason for rejection | NULLABLE |
| approved_by | BIGINT | Foreign Key to Users (approver) | FK, NULLABLE |
| assigned_timesheet_id | BIGINT | Foreign Key to AssignedTimesheets | FK, NULLABLE |
| created_at | TIMESTAMP | Record creation timestamp | DEFAULT CURRENT_TIMESTAMP |
| updated_at | TIMESTAMP | Last update timestamp | DEFAULT CURRENT_TIMESTAMP |

### Table 6: Performance
| Field Name | Data Type | Description | Constraints |
|-----------|-----------|-------------|------------|
| id | BIGINT | Primary Key | PK, Auto Increment |
| employee_id | BIGINT | Foreign Key to Employees | FK, NOT NULL |
| rating | INT | Performance rating (1-5 scale) | NOT NULL |
| comments | TEXT | Detailed performance feedback | NULLABLE |
| review_period | VARCHAR(100) | Review period (Q1, Q2, etc.) | NULLABLE |
| reviewed_by | BIGINT | Foreign Key to Users (reviewer) | FK, NULLABLE |
| review_date | DATE | Date of performance review | NOT NULL |
| created_at | TIMESTAMP | Record creation timestamp | DEFAULT CURRENT_TIMESTAMP |
| updated_at | TIMESTAMP | Last update timestamp | DEFAULT CURRENT_TIMESTAMP |

### Table 7: Violations
| Field Name | Data Type | Description | Constraints |
|-----------|-----------|-------------|------------|
| id | BIGINT | Primary Key | PK, Auto Increment |
| employee_id | BIGINT | Foreign Key to Employees | FK, NOT NULL |
| violation_type | VARCHAR(100) | Type of violation | NOT NULL |
| description | TEXT | Violation details | NULLABLE |
| violation_date | DATE | Date of violation | NOT NULL |
| status | VARCHAR(50) | Status (pending, resolved) | NOT NULL, Default: 'pending' |
| created_at | TIMESTAMP | Record creation timestamp | DEFAULT CURRENT_TIMESTAMP |
| updated_at | TIMESTAMP | Last update timestamp | DEFAULT CURRENT_TIMESTAMP |

### Table 8: Shifts
| Field Name | Data Type | Description | Constraints |
|-----------|-----------|-------------|------------|
| id | BIGINT | Primary Key | PK, Auto Increment |
| name | VARCHAR(100) | Shift name (e.g., Morning, Evening) | NOT NULL, UNIQUE |
| start_time | TIME | Shift start time | NOT NULL |
| end_time | TIME | Shift end time | NOT NULL |
| created_at | TIMESTAMP | Record creation timestamp | DEFAULT CURRENT_TIMESTAMP |
| updated_at | TIMESTAMP | Last update timestamp | DEFAULT CURRENT_TIMESTAMP |

### Table 9: HrmsNotifications
| Field Name | Data Type | Description | Constraints |
|-----------|-----------|-------------|------------|
| id | BIGINT | Primary Key | PK, Auto Increment |
| user_id | BIGINT | Foreign Key to Users | FK, NOT NULL |
| type | VARCHAR(50) | Notification type (leave, timesheet, approval) | NOT NULL |
| title | VARCHAR(255) | Notification title | NOT NULL |
| message | TEXT | Notification message | NOT NULL |
| is_read | BOOLEAN | Read status | Default: false |
| created_at | TIMESTAMP | Record creation timestamp | DEFAULT CURRENT_TIMESTAMP |
| updated_at | TIMESTAMP | Last update timestamp | DEFAULT CURRENT_TIMESTAMP |

### Table 10: AuditLogs
| Field Name | Data Type | Description | Constraints |
|-----------|-----------|-------------|------------|
| id | BIGINT | Primary Key | PK, Auto Increment |
| user_id | BIGINT | Foreign Key to Users | FK, NOT NULL |
| action | VARCHAR(255) | Action performed | NOT NULL |
| model | VARCHAR(255) | Model affected | NULLABLE |
| model_id | BIGINT | ID of affected model | NULLABLE |
| old_values | JSON | Previous values | NULLABLE |
| new_values | JSON | New values | NULLABLE |
| ip_address | VARCHAR(45) | IP address of action | NULLABLE |
| created_at | TIMESTAMP | Record creation timestamp | DEFAULT CURRENT_TIMESTAMP |

### Table 11: AssignedTimesheets
| Field Name | Data Type | Description | Constraints |
|-----------|-----------|-------------|------------|
| id | BIGINT | Primary Key | PK, Auto Increment |
| employee_id | BIGINT | Foreign Key to Employees | FK, NOT NULL |
| week_start | DATE | Week start date | NOT NULL |
| week_end | DATE | Week end date | NOT NULL |
| status | VARCHAR(50) | Status | NOT NULL |
| created_at | TIMESTAMP | Record creation timestamp | DEFAULT CURRENT_TIMESTAMP |
| updated_at | TIMESTAMP | Last update timestamp | DEFAULT CURRENT_TIMESTAMP |

### Table 12: UserRequests
| Field Name | Data Type | Description | Constraints |
|-----------|-----------|-------------|------------|
| id | BIGINT | Primary Key | PK, Auto Increment |
| user_id | BIGINT | Foreign Key to Users | FK, NOT NULL |
| type | VARCHAR(50) | Request type | NOT NULL |
| status | VARCHAR(50) | Status (pending, approved, denied) | NOT NULL, Default: 'pending' |
| created_at | TIMESTAMP | Record creation timestamp | DEFAULT CURRENT_TIMESTAMP |
| updated_at | TIMESTAMP | Last update timestamp | DEFAULT CURRENT_TIMESTAMP |

### Table 13: Permissions
| Field Name | Data Type | Description | Constraints |
|-----------|-----------|-------------|------------|
| id | BIGINT | Primary Key | PK, Auto Increment |
| name | VARCHAR(255) | Permission name | NOT NULL, UNIQUE |
| description | TEXT | Permission description | NULLABLE |
| created_at | TIMESTAMP | Record creation timestamp | DEFAULT CURRENT_TIMESTAMP |

### Table 14: RolePermissions
| Field Name | Data Type | Description | Constraints |
|-----------|-----------|-------------|------------|
| id | BIGINT | Primary Key | PK, Auto Increment |
| role | VARCHAR(50) | Role name | NOT NULL |
| permission_id | BIGINT | Foreign Key to Permissions | FK, NOT NULL |
| created_at | TIMESTAMP | Record creation timestamp | DEFAULT CURRENT_TIMESTAMP |

---

## 5. PROCESS FLOW DIAGRAMS

### 5.1 User Login Process

```
┌─────────────────────────┐
│  START: User Opens      │
│  HrMs Application       │
└──────────┬──────────────┘
           │
           ▼
┌─────────────────────────┐
│  Display Login Page     │
│  (Email & Password)     │
└──────────┬──────────────┘
           │
           ▼
┌─────────────────────────┐
│  User Enters Email &    │
│  Password, Clicks Login │
└──────────┬──────────────┘
           │
           ▼
┌─────────────────────────┐
│  System Validates       │
│  Credentials Against    │
│  Database               │
└──────────┬──────────────┘
           │
      ┌────┴─────────┐
      │               │
  Valid         Invalid
      │               │
      ▼               ▼
   ┌────┐      ┌──────────────┐
   │✓   │      │Show Error:   │
   │OK  │      │"Invalid      │
   └─┬──┘      │credentials"  │
     │         └──────┬───────┘
     │                │
     │                ▼
     │         ┌──────────────┐
     │         │Return to     │
     │         │Login Page    │
     │         │(User Retries)│
     │         └──────────────┘
     │
     ▼
┌─────────────────────────┐
│  Check Account Status   │
│  (Active/Pending/       │
│   Inactive)             │
└──────────┬──────────────┘
           │
    ┌──────┼──────┐
    │      │      │
 Active Pending Inactive
    │      │      │
    ▼      ▼      ▼
  ┌──┐ ┌──────┐ ┌───────┐
  │✓ │ │Setup │ │Access │
  │  │ │Prof. │ │Denied │
  └┬─┘ │      │ └───────┘
   │   │(if   │
   │   │new)  │
   │   └──┬───┘
   │      │
   ▼      ▼
┌──────────────────────────┐
│ Check User Role & Load   │
│ Appropriate Dashboard    │
│ (Super Admin/Sub Admin/  │
│  Employee)               │
└──────────┬───────────────┘
           │
           ▼
┌──────────────────────────┐
│ Set Session Token &      │
│ Redirect to Dashboard    │
└──────────┬───────────────┘
           │
           ▼
┌──────────────────────────┐
│ END: User Logged In &    │
│ Dashboard Displayed      │
└──────────────────────────┘
```

**Step-by-Step Flow Explanation:**

1. **User Opens System** — Employee accesses the HrMs login page via web browser
2. **System Displays Login Form** — Login page shows email and password input fields
3. **User Enters Credentials** — User enters their registered email and password, clicks "Login"
4. **System Validates Credentials** — System queries database to verify email and hashed password match
5. **Credential Check Decision** — If invalid, show error message and return to login; if valid, proceed
6. **Check Account Status** — System verifies if account is Active (can login), Pending (must complete setup), or Inactive (denied)
7. **Load User Role & Dashboard** — System determines user role (Super Admin/Sub Admin/Employee) and loads appropriate dashboard
8. **Create Session** — System generates secure session token and stores in browser cookies
9. **Redirect to Dashboard** — User is redirected to role-specific dashboard with authorized access

---

### 5.2 Employee Management (CRUD) - Adding New Employee

```
┌─────────────────────────┐
│ START: Admin Clicks     │
│ "Add Employee" Button   │
└──────────┬──────────────┘
           │
           ▼
┌─────────────────────────┐
│ Display Employee        │
│ Registration Form       │
│ (Personal, Employment,  │
│  Government IDs)        │
└──────────┬──────────────┘
           │
           ▼
┌─────────────────────────┐
│ Admin Fills Form with   │
│ Employee Information    │
│ • First/Last Name       │
│ • Email, Phone          │
│ • Department, Position  │
│ • Date Hired, Shift     │
│ • Government IDs        │
└──────────┬──────────────┘
           │
           ▼
┌─────────────────────────┐
│ Admin Clicks "Save"     │
│ Button                  │
└──────────┬──────────────┘
           │
           ▼
┌─────────────────────────┐
│ System Validates All    │
│ Required Fields         │
│ • Email format valid?   │
│ • Unique email?         │
│ • Required fields filled?
└──────────┬──────────────┘
           │
      ┌────┴──────────┐
      │               │
 Validation      Validation
  Success         Failed
      │               │
      ▼               ▼
   ┌────┐      ┌──────────────┐
   │✓   │      │Show Error:   │
   │OK  │      │List invalid  │
   └─┬──┘      │fields        │
     │         └──────┬───────┘
     │                │
     │                ▼
     │         ┌──────────────┐
     │         │User Corrects │
     │         │& Retries     │
     │         │(Loops back)  │
     │         └──────────────┘
     │
     ▼
┌─────────────────────────┐
│ System Creates Employee │
│ Record in Database      │
│ • Generate Employee ID  │
│ • Store all data        │
│ • Set profile_completed │
│   flag                  │
└──────────┬──────────────┘
           │
           ▼
┌─────────────────────────┐
│ System Creates          │
│ Associated User Account │
│ (if new user needed)    │
│ • Status: Pending       │
│ • Role: Unassigned      │
└──────────┬──────────────┘
           │
           ▼
┌─────────────────────────┐
│ System Saves to         │
│ Database & Creates      │
│ Audit Log Entry         │
└──────────┬──────────────┘
           │
           ▼
┌─────────────────────────┐
│ Show Success Message:   │
│ "Employee Added"        │
└──────────┬──────────────┘
           │
           ▼
┌─────────────────────────┐
│ Redirect to Employee    │
│ Profile Page            │
└──────────┬──────────────┘
           │
           ▼
┌─────────────────────────┐
│ END: Employee Record    │
│ Created & Visible in    │
│ Employee List           │
└─────────────────────────┘
```

**Step-by-Step Flow Explanation:**

1. **Admin Initiates Action** — Admin/Super Admin clicks "Add Employee" button in Employee Management module
2. **System Displays Form** — Multi-step form appears with fields for personal, employment, and government ID information
3. **Admin Fills Form** — Admin enters all employee details: name, contact info, department, position, hire date, shift assignment, government IDs
4. **Admin Submits Form** — Admin clicks "Save" button to submit the form data
5. **Validation Check** — System validates all required fields (email format, uniqueness, required data completeness)
6. **Validation Decision** — If validation fails, show error messages highlighting invalid fields; if passes, proceed
7. **User Corrects Errors** — Admin reviews error messages and corrects invalid data, then retries
8. **Create Employee Record** — System inserts employee record into database, generates unique employee ID, sets profile completion flag
9. **Create User Account** — System optionally creates associated user account with "Pending" status for account approval
10. **Log Action** — System creates audit log entry recording who added the employee and when
11. **Show Success** — System displays success confirmation message
12. **Redirect to Profile** — System redirects to newly created employee's profile page for verification
13. **Employee Visible** — Employee now appears in the Employee List and system records

---

### 5.3 Attendance Transaction Processing

```
┌─────────────────────────┐
│ START: Employee         │
│ Navigates to            │
│ Attendance Page         │
└──────────┬──────────────┘
           │
           ▼
┌─────────────────────────┐
│ System Checks If        │
│ Employee Already        │
│ Clocked In Today        │
└──────────┬──────────────┘
           │
      ┌────┴──────────┐
      │               │
   No Record       Record
   Exists          Exists
      │               │
      ▼               ▼
┌──────────────┐  ┌──────────────┐
│Show "Clock   │  │Show "Clock   │
│In" Button    │  │Out" Button & │
│with current  │  │"Already in"  │
│time          │  │badge         │
└──────┬───────┘  └──────┬───────┘
       │                 │
       ▼                 ▼
   ┌──────────┐      ┌──────────┐
   │Employee  │      │Employee  │
   │Clicks    │      │Clicks    │
   │"Clock In"│      │"Clock Out"
   └────┬─────┘      └────┬─────┘
        │                 │
        ▼                 ▼
   ┌─────────────────────────┐
   │ Confirm Action Dialog   │
   │ "Clock in at [time]?"   │
   │ [Confirm] [Cancel]      │
   └────┬────────────────────┘
        │
    ┌───┴───┐
    │       │
 Confirm Cancel
    │       │
    ▼       ▼
  ┌──┐   ┌────┐
  │✓ │   │Page│
  │  │   │stay│
  └┬─┘   │same│
   │     └────┘
   ▼
┌─────────────────────────┐
│ Get Current System Time │
│ (or use submitted time) │
└──────────┬──────────────┘
           │
           ▼
┌─────────────────────────┐
│ Validate Time:          │
│ • Not in future?        │
│ • Reasonable range?     │
│ • Within shift hours?   │
└──────────┬──────────────┘
           │
      ┌────┴──────────┐
      │               │
  Valid         Invalid
      │               │
      ▼               ▼
   ┌────┐      ┌──────────────┐
   │✓   │      │Show Error:   │
   │OK  │      │"Invalid time"│
   └─┬──┘      └──────┬───────┘
     │                │
     │                ▼
     │         ┌──────────────┐
     │         │Return to     │
     │         │Attendance    │
     │         │page (retry)  │
     │         └──────────────┘
     │
     ▼
┌──────────────────────────┐
│ Check Database for       │
│ Existing Record (to      │
│ prevent duplicates)      │
└──────────┬───────────────┘
           │
           ▼
┌──────────────────────────┐
│ Insert/Update Attendance │
│ Record                   │
│ • If first: Set time_in  │
│ • If second: Set time_out│
│ • Calculate hours_worked │
│ • Set status             │
└──────────┬───────────────┘
           │
           ▼
┌──────────────────────────┐
│ Create Database Trigger  │
│ Events (if configured)   │
│ • Validate no oversell   │
│ • Update leave if needed │
│ • Send notification      │
└──────────┬───────────────┘
           │
           ▼
┌──────────────────────────┐
│ Log Transaction to       │
│ Audit Trail              │
│ • User ID                │
│ • Action (clock in/out)  │
│ • Timestamp              │
│ • IP address             │
└──────────┬───────────────┘
           │
           ▼
┌──────────────────────────┐
│ Send Notification to     │
│ Employee & Management    │
│ "Clock in/out recorded"  │
└──────────┬───────────────┘
           │
           ▼
┌──────────────────────────┐
│ Update Dashboard Display │
│ • Show clock in/out time │
│ • Show hours worked      │
│ • Show attendance status │
└──────────┬───────────────┘
           │
           ▼
┌──────────────────────────┐
│ Show Success Message &   │
│ Updated Clock Display    │
│ with confirmation badge  │
└──────────┬───────────────┘
           │
           ▼
┌──────────────────────────┐
│ END: Attendance          │
│ Transaction Complete &   │
│ Record Saved             │
└──────────────────────────┘
```

**Step-by-Step Flow Explanation:**

1. **Employee Navigates to Attendance** — Employee opens Attendance page on their dashboard
2. **System Checks Today's Record** — System queries database to check if an attendance record exists for today
3. **Display Appropriate Button** — If no record exists, show "Clock In" button; if record exists, show "Clock Out" button
4. **Employee Initiates Action** — Employee clicks "Clock In" or "Clock Out" button
5. **Confirmation Dialog** — System displays confirmation dialog with current time asking "Clock in at [time]?"
6. **Employee Confirms or Cancels** — Employee confirms the action or cancels (returns to page without change)
7. **Get Current Time** — System retrieves the current system time (or uses employee-submitted time if allowed)
8. **Validate Time** — System validates time is not in future, within reasonable range, and (optionally) within shift hours
9. **Validation Decision** — If invalid, show error and return to attendance page for retry; if valid, proceed
10. **Check for Duplicates** — System queries database to prevent duplicate clock in/out records
11. **Create/Update Record** — System inserts new attendance record (clock in) or updates existing record (clock out) with calculated hours worked
12. **Execute Triggers** — Database triggers execute (validate no overselling, update leave status if needed)
13. **Log Transaction** — System records transaction in audit log with user ID, action, timestamp, and IP address
14. **Send Notification** — System sends real-time notification to employee and management about the clock action
15. **Update Display** — System refreshes employee dashboard to show updated clock times and hours worked
16. **Show Confirmation** — System displays success message and visual confirmation badge
17. **Transaction Complete** — Attendance data is saved to database and transaction is complete

---

### 5.4 User Registration & Approval Workflow

```
┌─────────────────────┐
│ New User Registers  │
│ (Email, Password)   │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│ Account Created     │
│ Status: Pending     │
│ Role: Unassigned    │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│ Email Verification  │
│ Sent to User        │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│ Super Admin Reviews │
│ Pending Requests    │
└──────────┬──────────┘
           │
      ┌────┴─────┐
      │           │
   Approve    Reject
      │           │
      ▼           ▼
┌─────────┐  ┌──────────┐
│Assign   │  │Account   │
│Role     │  │Denied    │
│Status:  │  └──────────┘
│Active   │
└────┬────┘
     │
     ▼
┌─────────────────────┐
│ User Can Login      │
│ with Assigned Role  │
└─────────────────────┘
```

**Step-by-Step Flow Explanation:**

1. **New User Registration** — Prospective employee/user visits the registration page and enters email and password to create an account
2. **Account Creation in Pending Status** — System creates user record in database with status set to "Pending" and role set to "Unassigned"
3. **Email Verification Sent** — System sends verification email to the registered email address with verification link
4. **User Verifies Email** — User clicks verification link in email to confirm email ownership
5. **Super Admin Review** — Super Admin receives notification of pending user account and reviews the registration request in the admin panel
6. **Approval Decision** — Super Admin approves the registration and assigns appropriate role (Sub Admin, Employee) OR rejects the request
7. **If Approved** — System updates account status to "Active", assigns the selected role, and sends confirmation email to user
8. **If Rejected** — System updates account status to "Denied" and sends rejection notification to user
9. **User Login Access** — If approved, user can now log in with their email and password and access the system with their assigned role permissions

### 5.2 Attendance Clock In/Out Process

```
┌──────────────────────┐
│ Employee Clicks      │
│ "Clock In" Button    │
└──────────┬───────────┘
           │
           ▼
┌──────────────────────┐
│ System Gets Current  │
│ Time (or input time) │
└──────────┬───────────┘
           │
           ▼
┌──────────────────────┐
│ Validate Time        │
│ (Not future time)    │
└──────────┬───────────┘
           │
      ┌────┴─────┐
      │           │
   Valid    Invalid
      │           │
      ▼           ▼
   ┌──┐      ┌─────────┐
   │OK│      │Show     │
   └──┘      │Error    │
      │      └─────────┘
      ▼
┌──────────────────────┐
│ Record Time In       │
│ Create Attendance    │
│ Record for Today     │
└──────────┬───────────┘
           │
           ▼
┌──────────────────────┐
│ Send Notification    │
│ to Employee          │
└──────────┬───────────┘
           │
           ▼
┌──────────────────────┐
│ Display "Clocked In" │
│ with Time & Button   │
│ to Clock Out         │
└──────────────────────┘
```

**Step-by-Step Flow Explanation:**

1. **Employee Initiates Clock In** — Employee navigates to the Attendance page and clicks the "Clock In" button
2. **System Retrieves Current Time** — System gets the current system time or allows employee to manually input their clock-in time
3. **Validate Time** — System validates that the time is not in the future and falls within a reasonable range
4. **Validation Decision** — If time is invalid (future time or outside range), show error message and prompt retry; if valid, proceed
5. **Create Attendance Record** — System creates a new attendance record in the database with the clock-in time and sets the status to "Present"
6. **Calculate Hours** — System prepares to track hours worked (hours_worked will be calculated when employee clocks out)
7. **Send Notification** — System sends real-time notification to the employee confirming successful clock-in
8. **Update Display** — System refreshes the attendance page to show "Clocked In" status with clock-in time and provides "Clock Out" button
9. **Record Complete** — Employee's attendance for the day is recorded; they can now click "Clock Out" when leaving

### 5.3 Leave Request & Approval Workflow

```
┌───────────────────────┐
│ Employee Submits      │
│ Leave Request         │
│ (dates, type, reason) │
└──────────┬────────────┘
           │
           ▼
┌───────────────────────┐
│ System Validates      │
│ - Date range valid?   │
│ - Leave balance OK?   │
│ - No conflicts?       │
└──────────┬────────────┘
           │
      ┌────┴─────┐
      │           │
   Valid    Invalid
      │           │
      ▼           ▼
┌─────────┐   ┌──────┐
│Save as  │   │Show  │
│Pending  │   │Error │
└────┬────┘   └──────┘
     │
     ▼
┌──────────────────────┐
│ Send Notification    │
│ to Admin             │
└────┬─────────────────┘
     │
     ▼
┌──────────────────────┐
│ Admin Reviews        │
│ Leave Request        │
└────┬─────────────────┘
     │
  ┌──┴──┐
  │     │
Approve Deny
  │     │
  ▼     ▼
┌──┐  ┌──────┐
│✓ │  │Store │
│  │  │reason│
└┬─┘  └──┬───┘
 │       │
 ▼       ▼
┌──────────────────────┐
│ Update Leave Status  │
│ Send Notification    │
│ to Employee          │
│ Update Leave Balance │
└──────────────────────┘
```

**Step-by-Step Flow Explanation:**

1. **Employee Submits Leave Request** — Employee navigates to Leave Management module and submits a new leave request with leave type (vacation, sick, personal), start date, end date, and reason
2. **System Validates Request** — System validates that the date range is valid (end date after start date), employee has sufficient leave balance, and there are no scheduling conflicts
3. **Validation Decision** — If validation fails (invalid dates, insufficient balance, or conflicts), show error message with specific reason; if valid, proceed
4. **Save as Pending** — System saves the leave request in the database with status set to "Pending" and creates a pending record
5. **Send Notification to Admin** — System sends notification to Sub Admin/Admin indicating a new leave request requires approval
6. **Admin Reviews Request** — Sub Admin/Admin accesses the Leave Approvals page and reviews the employee's leave request with all details
7. **Admin Decision** — Admin chooses to Approve (grants leave) or Deny (rejects leave request)
8. **If Approved** — System updates leave status to "Approved", decrements employee's leave balance, marks the requested dates as "On Leave", and sends approval notification to employee
9. **If Denied** — System updates leave status to "Denied", stores the reason for denial, keeps employee's leave balance unchanged, and sends denial notification with reason to employee
10. **Update Dashboard** — Employee's dashboard refreshes to show the leave approval status and updated leave balance
11. **Process Complete** — Leave request workflow is complete and recorded in the system

### 5.4 Timesheet Submission & Approval

```
┌─────────────────────┐
│ Employee Navigates  │
│ to Timesheet Module │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│ Enter Weekly Hours  │
│ & Task Details      │
│ for Each Day        │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│ System Calculates   │
│ Total Hours         │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│ Employee Submits    │
│ Timesheet (Pending) │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│ Notification Sent   │
│ to Admin            │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│ Admin Reviews       │
│ Timesheet Details   │
└──────────┬──────────┘
           │
      ┌────┴────┐
      │          │
  Approve    Reject
      │          │
      ▼          ▼
   ┌──┐    ┌──────────┐
   │✓ │    │Document  │
   └┬─┘    │Reason    │
    │      └────┬─────┘
    │           │
    ▼           ▼
┌──────────────────────┐
│ Update Status        │
│ Send Notification    │
│ to Employee          │
└──────────────────────┘
```

**Step-by-Step Flow Explanation:**

1. **Employee Opens Timesheet Module** — Employee accesses the Timesheet section to submit weekly work hours
2. **View Weekly Timesheet Form** — System displays a form for the current week showing dates (Monday-Sunday) with fields for hours worked and task descriptions for each day
3. **Employee Enters Hours & Tasks** — Employee enters the number of hours worked each day and descriptions of tasks/projects worked on
4. **System Calculates Total** — System automatically calculates the total hours worked for the week by summing daily hours
5. **Employee Submits Timesheet** — Employee clicks "Submit" button to submit the timesheet for admin approval; status is set to "Pending"
6. **Validation Check** — System validates that required fields are filled and reasonable hours are entered (not excessive)
7. **Send Admin Notification** — System sends notification to Sub Admin/Admin that a timesheet requires review and approval
8. **Admin Reviews Timesheet** — Admin opens the pending timesheet and reviews employee's hours, task details, and total hours
9. **Admin Approval Decision** — Admin chooses to Approve (accept timesheet) or Reject (request revision)
10. **If Approved** — System updates timesheet status to "Approved", records the approval, and sends confirmation notification to employee
11. **If Rejected** — System updates timesheet status to "Rejected", stores the rejection reason, and sends notification to employee asking them to revise and resubmit
12. **Update Employee Dashboard** — Employee dashboard updates to reflect timesheet status (approved/rejected/pending)
13. **Process Complete** — Timesheet submission and approval workflow is complete

---

## 6. TECHNOLOGY STACK

| Component | Technology | Version |
|-----------|-----------|---------|
| **Backend Framework** | Laravel | 12 |
| **PHP Version** | PHP | 8.2 |
| **Database** | MySQL | 8.0+ |
| **Frontend** | Blade Templates | Laravel 12 |
| **CSS Framework** | Tailwind CSS | 4 |
| **Frontend Scripting** | Vanilla JavaScript | ES6+ |
| **Authentication** | Laravel Breeze | v2 |
| **Package Manager (PHP)** | Composer | Latest |
| **Package Manager (JS)** | NPM | Latest |
| **Build Tool** | Vite | Latest |
| **Code Formatter** | Laravel Pint | v1 |
| **Testing Framework** | PHPUnit | v11 |
| **Development Server** | PHP Built-in / Sail | Latest |
| **Version Control** | Git | Latest |
| **IDE** | VS Code / PHPStorm | Latest |

**Development Environment:**
- Local: PHP 8.2, MySQL 8.0, Composer, Node.js
- Docker: Laravel Sail (optional containerized development)
- Database: MySQL with migration-based schema management
- Deployment: Linux server (Ubuntu 20.04+) or Windows Server 2019+

---

## 7. UI PROTOTYPES & DESIGNS

### SECTION 7.1: DASHBOARD UI

#### 7.1.1 Admin Dashboard

**Dashboard Layout & Navigation:**
```
┌──────────────────────────────────────────────────────────────┐
│ HRMS Admin Dashboard                    [Profile ▼] [Logout] │
├──────────────────────────────────────────────────────────────┤
│ ┌─────────────┐                                              │
│ │ NAVIGATION  │  Dashboard > Employees > Attendance > Leave  │
│ │   MENU      │  Timesheets > Performance > Violations       │
│ │             │  Users > Reports > Settings                  │
│ │             │                                              │
│ │ • Dashboard │                                              │
│ │ • Employees │                                              │
│ │ • Attendance│                                              │
│ │ • Leave     │                                              │
│ │ • Timesheet │                                              │
│ │ • Settings  │                                              │
│ └─────────────┘                                              │
└──────────────────────────────────────────────────────────────┘
```

**Key Summary Cards (KPI Overview):**
```
┌─────────────────────────────────────────────────────────────┐
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐       │
│  │ Total Active │  │ Present Today│  │ Late Arrivals│       │
│  │ Employees    │  │              │  │              │       │
│  │     125      │  │      98      │  │      12      │       │
│  │  [↗ +3 new] │  │  [78.4% avg] │  │ [9.6% avg]   │       │
│  └──────────────┘  └──────────────┘  └──────────────┘       │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐       │
│  │Absent Today  │  │On Leave      │  │Pending       │       │
│  │              │  │              │  │Approvals     │       │
│  │      8       │  │      7       │  │      3       │       │
│  │  [6.4% avg]  │  │  [5.6% avg]  │  │  [New]       │       │
│  └──────────────┘  └──────────────┘  └──────────────┘       │
└─────────────────────────────────────────────────────────────┘
```

**What Information is Displayed:**
- **Total Active Employees** — Count of all currently employed staff with trend indicator (new hires this month)
- **Present Today** — Real-time count of employees who have clocked in today with percentage vs. total workforce
- **Late Arrivals** — Count of employees who clocked in after shift start time with average late arrival rate
- **Absent Employees** — Count of employees not present today with absence rate percentage
- **Employees On Leave** — Count of approved leave records active today with leave rate percentage
- **Pending Approvals** — Number of pending leave requests, timesheets, and user registrations requiring action

**Why This is Important:**
These summary cards provide at-a-glance visibility into the organization's staffing status, enabling quick identification of attendance issues, staffing gaps, and pending administrative tasks that need attention.

---

**Quick Action Widgets:**
```
┌─────────────────────────────────────────────────────────────┐
│ PENDING ACTIONS                                             │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│ 🔔 Pending Leave Approvals (3)                             │
│    • Jane Smith - Vacation (05/20-05/22)                  │
│    • John Doe - Sick Leave (05/18)                        │
│    [View All >]                                            │
│                                                             │
│ 📋 Pending Timesheet Approvals (5)                        │
│    • Week 05/12-05/18: 8 pending from IT Dept            │
│    • Week 05/05-05/11: 2 pending from HR Dept            │
│    [View All >]                                            │
│                                                             │
│ 👤 Pending User Account Approvals (1)                     │
│    • bea.ramos@hrms.ph (Employee) - Registered 05/17    │
│    [View All >]                                            │
│                                                             │
│ ⚠️  Attendance Alerts                                      │
│    • Low Attendance Alert: IT Department (72% avg)       │
│    • Chronic Absenteeism: 3 employees (>10 abs/month)   │
│    [View Details >]                                        │
└─────────────────────────────────────────────────────────────┘
```

**What Information is Displayed:**
- **Pending Leave Approvals** — List of unapproved leave requests with employee name, leave type, and requested dates
- **Pending Timesheet Approvals** — Count of submitted timesheets awaiting admin review by week and department
- **Pending User Accounts** — New user registrations awaiting approval with role assignment
- **Attendance Alerts** — Automated alerts for departments with low attendance or employees with chronic absenteeism patterns

**Why This is Important:**
These action widgets focus admin attention on tasks requiring immediate action, preventing requests from getting lost and ensuring timely approvals that keep the organization running smoothly.

---

**Charts & Analytics:**
```
┌──────────────────────────────────────────────────────────────┐
│ ANALYTICS & TRENDS                                           │
├──────────────────────────────────────────────────────────────┤
│                                                              │
│  Weekly Attendance Trend          Department Attendance %    │
│  ┌─────────────────────┐         ┌─────────────────────┐    │
│  │ 100%│                │         │ HR:  85% ▓▓▓▓▓░░░░ │    │
│  │  80%│    ╱╲    ╱╲    │         │ IT:  72% ▓▓▓▓░░░░░ │    │
│  │  60%│   ╱  ╲  ╱  ╲   │         │ Sales: 90% ▓▓▓▓▓░░░│    │
│  │  40%│  ╱    ╲╱    ╲  │         │ Ops: 88% ▓▓▓▓▓░░░░│    │
│  │  20%│                │         │ Finance: 92% ▓▓▓▓▓░░│    │
│  │   0%│────────────────│         └─────────────────────┘    │
│  │    Mon Tue Wed Thu Fri│                                   │
│  └─────────────────────┘         Leave Usage by Type         │
│                                   ┌─────────────────────┐    │
│  Timesheet Status                 │ Vacation: 45 days   │    │
│  ┌─────────────────────┐         │ Sick: 12 days       │    │
│  │ ✓ Approved: 85      │         │ Personal: 8 days    │    │
│  │ ⏳ Pending: 12       │         │ Other: 5 days       │    │
│  │ ✗ Rejected: 3       │         └─────────────────────┘    │
│  └─────────────────────┘                                    │
└──────────────────────────────────────────────────────────────┘
```

**What Information is Displayed:**
- **Weekly Attendance Trend** — Line chart showing attendance percentage over the past 5 weeks for trend analysis
- **Department Attendance Distribution** — Horizontal bar chart showing attendance rates by department for comparison
- **Leave Usage by Type** — Pie/stacked chart showing how leave days are distributed across leave types
- **Timesheet Submission Status** — Summary showing approved, pending, and rejected timesheets for the current week

**Why This is Important:**
These charts provide trend analysis and comparative data, helping admins identify patterns (declining attendance, department-level issues, overuse of specific leave types) and make data-driven decisions about workforce management and policy adjustments.

---

**Recent Activity Log:**
```
┌──────────────────────────────────────────────────────────────┐
│ RECENT SYSTEM ACTIVITY                                       │
├──────────────────────────────────────────────────────────────┤
│ 05/19 10:45 AM  ✓ Employee Approved      Juan Dela Cruz      │
│                    (Employee) account activated             │
│ 05/19 10:30 AM  📋 Timesheet Approved    Week 05/12-05/18   │
│                    Jane Smith's timesheet (40 hrs)          │
│ 05/19 09:15 AM  ✓ Leave Approved        Vacation Leave      │
│                    Carlo Reyes (05/20-05/22)                │
│ 05/18 04:45 PM  👤 Employee Added        Maria Santos       │
│                    New hire in Sales Dept                   │
│ 05/18 02:30 PM  ✗ Leave Denied          Personal Leave      │
│                    Ana Ramos (Conflict with scheduled shift)│
│ [View Complete Log >]                                        │
└──────────────────────────────────────────────────────────────┘
```

**What Information is Displayed:**
- **Timestamp** — Date and time of each activity
- **Action Type** — Icon and description of the action (approval, addition, modification)
- **Details** — Specific information about what was done and who it affects
- **Link to Details** — Option to view complete log with filters

**Why This is Important:**
The activity log provides a continuous audit trail showing what administrative actions have been taken, helping admins verify that tasks have been completed and maintaining a record for compliance and accountability.

---

### 7.2 Sub Admin / Manager Dashboard

**Dashboard Layout & Navigation:**
```
┌──────────────────────────────────────────────────────────────┐
│ HRMS Sub Admin Dashboard            [Profile ▼] [Logout]    │
├──────────────────────────────────────────────────────────────┤
│ ┌─────────────┐                                              │
│ │ NAVIGATION  │  Dashboard > Employees > Attendance > Leave  │
│ │   MENU      │  Timesheets > Performance > Reports          │
│ │             │                                              │
│ │ • Dashboard │                                              │
│ │ • Employees │                                              │
│ │ • Attendance│                                              │
│ │ • Leave     │                                              │
│ │ • Timesheet │                                              │
│ │ • Reports   │                                              │
│ └─────────────┘                                              │
└──────────────────────────────────────────────────────────────┘
```

**Department KPI Summary Cards:**
```
┌─────────────────────────────────────────────────────────────┐
│ DEPARTMENT OVERVIEW                                         │
├─────────────────────────────────────────────────────────────┤
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐      │
│  │Team Members  │  │ Present Today│  │ Absent Today │      │
│  │              │  │              │  │              │      │
│  │     32       │  │      28      │  │      2       │      │
│  │ [Active]     │  │  [87.5%]     │  │  [6.25%]     │      │
│  └──────────────┘  └──────────────┘  └──────────────┘      │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐      │
│  │Late Arrivals │  │On Leave      │  │Pending       │      │
│  │              │  │              │  │Approvals     │      │
│  │      2       │  │      2       │  │      2       │      │
│  │  [Today]     │  │  [Today]     │  │  [Today]     │      │
│  └──────────────┘  └──────────────┘  └──────────────┘      │
└─────────────────────────────────────────────────────────────┘
```

**What Information is Displayed:**
- **Team Member Count** — Total employees in the department
- **Present Today** — Employees who have clocked in with percentage
- **Absent Today** — Employees not present with reason category
- **Late Arrivals** — Employees who clocked in late
- **On Leave** — Employees on approved leave
- **Pending Approvals** — Leaves and timesheets awaiting this manager's action

**Why This is Important:**
Department-level KPIs allow managers to monitor their team's attendance and quickly identify issues specific to their department without being overwhelmed with organization-wide data.

---

**Action Items & Pending Approvals:**
```
┌──────────────────────────────────────────────────────────────┐
│ MY ACTION ITEMS                                              │
├──────────────────────────────────────────────────────────────┤
│                                                              │
│ 📝 Leave Requests Requiring Approval (2)                   │
│    ☐ Jane Smith - Vacation (05/20-05/22) - Requested 05/17│
│    ☐ John Doe - Sick Leave (05/18) - Requested 05/18      │
│    [Approve/Deny These >]                                   │
│                                                              │
│ 📋 Timesheets Awaiting Review (3)                          │
│    ☐ Week 05/12-05/18: 3 pending submissions              │
│    Average hours: 38.5 hrs                                 │
│    [Review & Approve >]                                     │
│                                                              │
│ 👥 Team Member Requests (1)                                │
│    ☐ Maria Santos - Performance review schedule           │
│    [Handle >]                                               │
│                                                              │
└──────────────────────────────────────────────────────────────┘
```

**What Information is Displayed:**
- **Leave Requests** — Pending leave submissions requiring manager approval with employee name, type, and dates
- **Timesheet Reviews** — Submitted timesheets ready for manager approval with week period and hours
- **Team Requests** — Other items (performance reviews, schedule changes) needing manager attention

**Why This is Important:**
Grouping action items helps managers prioritize their approval tasks and ensures timely response to employee requests, reducing delays in leave approvals and timesheet processing.

---

**Department Reports & Analytics:**
```
┌──────────────────────────────────────────────────────────────┐
│ DEPARTMENT ANALYTICS                                         │
├──────────────────────────────────────────────────────────────┤
│                                                              │
│ Department Attendance Trend      Team Leave Usage           │
│ ┌─────────────────────┐         ┌──────────────────────┐   │
│ │ 95%│       ╱╲       │         │ Vacation: 15 days    │   │
│ │ 90%│      ╱  ╲  ╱╲  │         │ Sick: 3 days         │   │
│ │ 85%│ ╱╲  ╱    ╲╱  ╲ │         │ Personal: 2 days     │   │
│ │ 80%│ ──────────────│         │ Other: 1 day         │   │
│ │ 75%│                │         └──────────────────────┘   │
│ │    │─ W1 W2 W3 W4  │                                      │
│ └─────────────────────┘         Top Performers             │
│                                  ┌──────────────────────┐   │
│ Timesheet Completion             │ 1. Jane Smith        │   │
│ ┌─────────────────────┐         │    (Avg: 42 hrs/wk)  │   │
│ │ ✓ On Time: 28       │         │ 2. John Doe          │   │
│ │ ⏳ Late: 4          │         │    (Avg: 40 hrs/wk)  │   │
│ │ ✗ Overdue: 0        │         │ 3. Maria Santos      │   │
│ └─────────────────────┘         │    (Avg: 38 hrs/wk)  │   │
│                                  └──────────────────────┘   │
└──────────────────────────────────────────────────────────────┘
```

**What Information is Displayed:**
- **Department Attendance Trend** — Weekly attendance rate for the department over past month
- **Team Leave Usage** — Breakdown of leave days used by type
- **Timesheet Completion** — Status of timesheet submissions (on-time, late, overdue)
- **Top Performers** — Employees with highest productivity/hours worked

**Why This is Important:**
Department-specific analytics help managers identify team trends, recognize high performers, and spot attendance or productivity issues within their team for timely intervention.

---

### 7.3 Employee Dashboard

**Dashboard Layout & Navigation:**
```
┌──────────────────────────────────────────────────────────────┐
│ HRMS Employee Dashboard             [Profile ▼] [Logout]    │
├──────────────────────────────────────────────────────────────┤
│ ┌─────────────┐                                              │
│ │ NAVIGATION  │  Dashboard > Attendance > Leave > Timesheet │
│ │   MENU      │  Performance > Profile > Notifications      │
│ │             │                                              │
│ │ • Dashboard │                                              │
│ │ • Attendance│                                              │
│ │ • Leave     │                                              │
│ │ • Timesheet │                                              │
│ │ • Performance│                                             │
│ │ • Profile   │                                              │
│ └─────────────┘                                              │
└──────────────────────────────────────────────────────────────┘
```

**Today's Attendance Status Card:**
```
┌──────────────────────────────────────────────────────────────┐
│ TODAY'S ATTENDANCE                                           │
├──────────────────────────────────────────────────────────────┤
│                                                              │
│  Digital Clock: 10:45:32 AM                                │
│  ┌──────────────────────────────────────────────┐           │
│  │  🕐  10:45 AM                                 │           │
│  └──────────────────────────────────────────────┘           │
│                                                              │
│  Your Shift: 8:00 AM - 5:00 PM (Morning Shift)             │
│  Status: ✓ Present                                          │
│  Clocked In: 08:00 AM                                       │
│  Time Remaining: 7h 15m                                     │
│                                                              │
│  [🔴 Clock Out Button]                                      │
│                                                              │
└──────────────────────────────────────────────────────────────┘
```

**What Information is Displayed:**
- **Live Digital Clock** — Current system time in real-time
- **Shift Information** — Employee's assigned shift time (start and end times)
- **Attendance Status** — Present, Late, Absent, Half Day, On Leave
- **Clock In/Out Times** — When employee clocked in and (if applicable) clocked out
- **Hours Worked** — Time remaining in current shift or total hours worked
- **Quick Action Button** — Large button for Clock Out action

**Why This is Important:**
The attendance status widget gives employees immediate visibility into their attendance status and remaining work time, enabling them to manage their schedule and confirm clock transactions.

---

**Personal Quick Reference Widgets:**
```
┌──────────────────────────────────────────────────────────────┐
│ PERSONAL INFORMATION SUMMARY                                │
├──────────────────────────────────────────────────────────────┤
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐      │
│  │Leave Balance │  │ Recent       │  │ Pending      │      │
│  │              │  │Attendance    │  │ Actions      │      │
│  │  15 / 20     │  │ Record       │  │              │      │
│  │  days        │  │              │  │      0       │      │
│  │              │  │ Last 5 Days: │  │ [None]       │      │
│  │[View Details]│  │ ✓✓✓✓✓        │  │              │      │
│  └──────────────┘  └──────────────┘  └──────────────┘      │
└──────────────────────────────────────────────────────────────┘
```

**What Information is Displayed:**
- **Leave Balance** — Total leave days available and days remaining with visual indicator
- **Recent Attendance** — Visual representation (icons) of last 5 days' attendance status
- **Pending Actions** — Count of pending approvals or submissions waiting for action

**Why This is Important:**
These quick reference widgets allow employees to quickly check their leave balance before submitting requests and see their recent attendance history without navigating to separate pages.

---

**Personal Activity & Requests:**
```
┌──────────────────────────────────────────────────────────────┐
│ MY ACTIVITIES                                                │
├──────────────────────────────────────────────────────────────┤
│                                                              │
│ 📝 Leave Requests (Recent)                                 │
│    ✓ Vacation (05/20-05/22) - Approved 05/18             │
│    ⏳ Sick Leave (05/25) - Pending approval               │
│    [View All Leave History >]                              │
│                                                              │
│ 📋 Timesheet Submissions (Recent)                          │
│    ✓ Week 05/12-05/18 - Approved (40 hrs)               │
│    ✓ Week 05/05-05/11 - Approved (40 hrs)               │
│    ⏳ Week 04/28-05/04 - Pending (39 hrs)                │
│    [View Timesheet History >]                              │
│                                                              │
│ ⭐ Performance Reviews                                       │
│    Last Review: Q1 2026 (Rating: 4.2/5)                   │
│    Review Period: Q2 2026                                  │
│    [View Performance History >]                             │
│                                                              │
│ 🔔 Notifications (5 new)                                   │
│    • Leave Approved - Vacation (05/18)                    │
│    • Timesheet Submitted - Week 05/12-05/18 (05/18)      │
│    [View All Notifications >]                              │
│                                                              │
└──────────────────────────────────────────────────────────────┘
```

**What Information is Displayed:**
- **Leave Request Status** — Recent leave submissions with approval status
- **Timesheet Submissions** — Recent timesheet submissions showing approval status and hours
- **Performance Reviews** — Last review rating and next review period
- **Notifications** — Recent system notifications about approvals and submissions

**Why This is Important:**
This activity summary keeps employees informed about the status of their requests without needing to navigate to separate pages, improving self-service efficiency and reducing status inquiry emails to HR.

---

## 8. MAIN TRANSACTION PROTOTYPES

### 8.1 ATTENDANCE MANAGEMENT

#### Screen 1: View Attendance Records (Read)
```
┌─────────────────────────────────────┐
│ ATTENDANCE TRACKING                 │
├─────────────────────────────────────┤
│ [Search] [Date Range] [Department]  │
│ [Status Filter] ▼ [Apply] [Reset]   │
├─────────────────────────────────────┤
│ 543 records found                   │
├─────────────────────────────────────┤
│Employee │Dept│Shift │Time In│Time Out│Hrs │Sts│
├─────────────────────────────────────┤
│John Doe │HR  │8-5PM │09:00 │17:30   │8.5 │✓ │
│Jane Smith│IT │8-5PM │08:45 │17:15   │8.5 │✓ │
│...                                  │
└─────────────────────────────────────┘
[Pagination controls]
```
**Fields:** Employee name, department, shift, date, time-in, time-out, hours worked, status
**Purpose:** View and track daily attendance records with filtering and search capabilities

#### Screen 2: Clock In/Out (Create/Update)
```
┌─────────────────────────────────────┐
│ CLOCK IN / CLOCK OUT                │
├─────────────────────────────────────┤
│ Current Time: 09:15:42              │
│ Your Shift: 8:00 AM - 5:00 PM       │
│                                     │
│ Clocked In:  09:00 AM               │
│ [Clock Out Button]                  │
│ Hours Worked: 8h 30m                │
│ Status: ✓ Present                   │
└─────────────────────────────────────┘
```
**Fields:** Current time, shift info, clock in time, clock out time, hours calculation, status
**Purpose:** Allow employees to record daily work hours in real-time

#### Screen 3: Edit Attendance (Update)
```
┌─────────────────────────────────────┐
│ EDIT ATTENDANCE RECORD              │
├─────────────────────────────────────┤
│ Employee: John Doe                  │
│ Date: [05/18/2026] ────────────────│
│ Status: [Dropdown]                  │
│   ○ Present   ○ Late                │
│   ○ Absent    ○ Half Day            │
│   ○ On Leave                        │
│ Time In: [09:15]                   │
│ Time Out: [17:45]                  │
│ Notes: [Text area]                  │
├─────────────────────────────────────┤
│ [Save] [Cancel]                     │
└─────────────────────────────────────┘
```
**Fields:** Employee, date, status, time-in, time-out, notes
**Purpose:** Allow admins to correct or update attendance records

---

### 8.2 LEAVE MANAGEMENT

#### Screen 1: Request Leave (Create)
```
┌─────────────────────────────────────┐
│ REQUEST LEAVE                       │
├─────────────────────────────────────┤
│ Leave Type: [Dropdown▼]             │
│ Start Date: [05/20/2026] ─────────│
│ End Date: [05/22/2026] ────────────│
│ Number of Days: 3                   │
│ Reason: [Text area]                │
│                                     │
│ Your Leave Balance: 15 / 20 days    │
├─────────────────────────────────────┤
│ [Submit Request] [Cancel]           │
└─────────────────────────────────────┘
```
**Fields:** Leave type, start date, end date, days calculation, reason, leave balance display
**Purpose:** Allow employees to submit leave requests with reason documentation

#### Screen 2: View Leave History (Read)
```
┌─────────────────────────────────────┐
│ LEAVE HISTORY                       │
├─────────────────────────────────────┤
│ Total Balance: 15 days              │
│ Used: 5 days  Remaining: 15 days    │
├─────────────────────────────────────┤
│Type │Start Date │End Date │Days│Sts │
├─────────────────────────────────────┤
│Sick │05/15/2026│05/15/2026│1  │✓App│
│Vac  │05/20/2026│05/22/2026│3  │Pend│
│Pers │05/10/2026│05/10/2026│1  │✗Den│
│...                                 │
└─────────────────────────────────────┘
```
**Fields:** Leave type, date range, status, days used, approval information
**Purpose:** View personal leave history and track remaining balance

#### Screen 3: Approve/Deny Leave (Admin Update)
```
┌─────────────────────────────────────┐
│ LEAVE APPROVAL                      │
├─────────────────────────────────────┤
│ Employee: Jane Smith                │
│ Type: Vacation Leave                │
│ Period: 05/20 - 05/22 (3 days)     │
│ Reason: Family vacation             │
│ Requested On: 05/18/2026            │
├─────────────────────────────────────┤
│ Action:                             │
│ [✓ Approve] [✗ Deny]               │
│ (if deny) Reason: [Text area]      │
├─────────────────────────────────────┤
│ [Submit] [Cancel]                   │
└─────────────────────────────────────┘
```
**Fields:** Employee details, leave info, approval/denial action, optional reason
**Purpose:** Allow admins to review and approve/deny leave requests

---

### 8.3 EMPLOYEE MANAGEMENT

#### Screen 1: Employee List (Read)
```
┌─────────────────────────────────────┐
│ EMPLOYEE MANAGEMENT                 │
├─────────────────────────────────────┤
│ [Search] [Department▼] [Status▼]    │
│ [Add Employee Button]               │
├─────────────────────────────────────┤
│ 125 employees total                 │
├─────────────────────────────────────┤
│Name   │Employee ID │Department│Status│
├─────────────────────────────────────┤
│John   │EMP-001    │IT       │Active│
│Jane   │EMP-002    │HR       │Active│
│...                                 │
│                                     │
│ [Prev] Page 1 of 5 [Next]          │
└─────────────────────────────────────┘
```
**Fields:** Name, employee ID, department, position, status, contact info
**Purpose:** View all employees with basic info and quick filters

#### Screen 2: Add/Edit Employee (Create/Update)
```
┌─────────────────────────────────────┐
│ ADD EMPLOYEE                        │
├─────────────────────────────────────┤
│ PERSONAL INFORMATION                │
│ First Name: [________________]      │
│ Last Name: [________________]       │
│ Email: [________________]           │
│ Phone: [________________]           │
│ Date of Birth: [__/__/__]          │
│ Address: [________________]         │
│                                     │
│ EMPLOYMENT INFORMATION              │
│ Employee ID: [________________]     │
│ Department: [HR ▼]                 │
│ Position: [________________]        │
│ Date Hired: [__/__/__]             │
│ Contract Expiry: [__/__/__]        │
│ Shift: [Morning ▼]                 │
│                                     │
│ GOVERNMENT IDs (Optional)           │
│ SSS: [________________]             │
│ PagIBIG: [________________]         │
│ PhilHealth: [________________]      │
│                                     │
│ [Save] [Cancel]                     │
└─────────────────────────────────────┘
```
**Fields:** Full name, email, phone, address, DOB, employee ID, department, position, date hired, contract expiry, shift, government IDs
**Purpose:** Create and manage complete employee profiles

#### Screen 3: Employee Profile (Read/Update)
```
┌─────────────────────────────────────┐
│ EMPLOYEE PROFILE                    │
├─────────────────────────────────────┤
│ Name: John Doe                      │
│ Employee ID: EMP-001                │
│ Status: Active                      │
│ Department: IT                      │
│ Position: Developer                 │
│                                     │
│ Email: john@company.com             │
│ Phone: +63-555-1234                │
│ DOB: 01/15/1990 (34 years old)     │
│ Date Hired: 05/01/2020 (6 years)   │
│ Current Shift: Morning (8-5PM)      │
│                                     │
│ Recent Attendance (Last 7 days):    │
│ [Chart showing attendance pattern]  │
│ Leave Balance: 15 / 20 days         │
│                                     │
│ [Edit] [Deactivate] [More ▼]       │
└─────────────────────────────────────┘
```
**Fields:** All personal/employment info, status, contact, years of service, recent activities
**Purpose:** View comprehensive employee profile and take admin actions

---

### 8.4 TIMESHEET MANAGEMENT

#### Screen 1: Submit Timesheet (Create)
```
┌─────────────────────────────────────┐
│ SUBMIT TIMESHEET                    │
├─────────────────────────────────────┤
│ Week of: 05/12/2026 - 05/18/2026   │
├─────────────────────────────────────┤
│Date │Mon│Tue│Wed│Thu│Fri│Sat│Sun   │
│Hrs  │ 8 │ 8 │ 8 │ 8 │ 8 │ 0 │ 0    │
│Task │PRJ│PRJ│PRJ│PRJ│PRJ│ - │ -    │
│     │ABC│ABC│ABC│ABC│ABC│   │      │
│                                     │
│ Total Hours: 40                     │
│ [Submit] [Save Draft] [Cancel]     │
└─────────────────────────────────────┘
```
**Fields:** Week period, daily hours, task descriptions, total calculation
**Purpose:** Allow employees to submit weekly work hours and task details

#### Screen 2: View Timesheets (Read)
```
┌─────────────────────────────────────┐
│ TIMESHEET HISTORY                   │
├─────────────────────────────────────┤
│Week of │ Hours │ Status │ Action    │
├─────────────────────────────────────┤
│05/12-18│  40   │✓Approved│ View    │
│05/05-11│  40   │✓Approved│ View    │
│04/28-04│  36   │⏳Pending│View/Edit│
│04/21-27│  40   │✗Rejected│ View    │
│...                                 │
└─────────────────────────────────────┘
```
**Fields:** Week period, total hours, status, submission/approval date
**Purpose:** Track timesheet history and current status

#### Screen 3: Approve Timesheet (Admin Update)
```
┌─────────────────────────────────────┐
│ APPROVE TIMESHEET                   │
├─────────────────────────────────────┤
│ Employee: Jane Smith                │
│ Week of: 05/12/2026 - 05/18/2026   │
│ Total Hours: 40                     │
│ Submitted: 05/19/2026               │
│                                     │
│ [View Details] [Show Tasks]         │
│                                     │
│ Action:                             │
│ [✓ Approve] [✗ Reject]             │
│ (if reject) Reason: [Text area]    │
│                                     │
│ [Submit] [Cancel]                   │
└─────────────────────────────────────┘
```
**Fields:** Employee name, week, hours, tasks, approval action, comments
**Purpose:** Allow admins to review and approve/reject submitted timesheets

---

### 8.5 PERFORMANCE MANAGEMENT

#### Screen 1: Create Performance Review (Create)
```
┌─────────────────────────────────────┐
│ NEW PERFORMANCE REVIEW              │
├─────────────────────────────────────┤
│ Employee: [Search/Select ▼]         │
│ Review Period: [Q2 2026 ▼]         │
│ Review Date: [05/18/2026]          │
│                                     │
│ PERFORMANCE RATINGS (1-5 stars)     │
│ Overall: [5 ★★★★★]               │
│ Quality: [4 ★★★★☆]               │
│ Reliability: [4 ★★★★☆]           │
│ Teamwork: [5 ★★★★★]              │
│ Communication: [4 ★★★★☆]         │
│                                     │
│ FEEDBACK & COMMENTS                 │
│ [Large text area for detailed      │
│ feedback and observations]          │
│                                     │
│ [Save] [Cancel]                     │
└─────────────────────────────────────┘
```
**Fields:** Employee select, review period, rating scale, detailed comments
**Purpose:** Document and record employee performance evaluations

#### Screen 2: View Performance History (Read)
```
┌─────────────────────────────────────┐
│ PERFORMANCE HISTORY                 │
├─────────────────────────────────────┤
│Period │ Avg Rating │ Reviewer │Date  │
├─────────────────────────────────────┤
│Q2 2026│    4.2     │ Manager  │05/18│
│Q1 2026│    4.0     │ Manager  │03/15│
│Q4 2025│    3.8     │ Manager  │12/20│
│Q3 2025│    3.9     │ Manager  │09/18│
│                                     │
│ Average Trend: 📈 Improving         │
│ [View Details] [Download]           │
└─────────────────────────────────────┘
```
**Fields:** Review period, rating, reviewer, review date, comments link
**Purpose:** Track performance history and trends over time

---

### 8.6 VIOLATION TRACKING

#### Screen 1: Record Violation (Create)
```
┌─────────────────────────────────────┐
│ RECORD VIOLATION                    │
├─────────────────────────────────────┤
│ Employee: [Search/Select ▼]         │
│ Violation Type: [Tardiness ▼]      │
│ Violation Date: [05/15/2026]       │
│ Description: [Text area]            │
│ Status: [Pending ▼]                │
│                                     │
│ [Save] [Cancel]                     │
└─────────────────────────────────────┘
```
**Fields:** Employee, violation type, date, detailed description, severity
**Purpose:** Record disciplinary actions and violations

#### Screen 2: View Violations (Read)
```
┌──────────────────────────────────┐
│ VIOLATION RECORDS                │
├──────────────────────────────────┤
│Employee │Type │ Date │ Status   │
├──────────────────────────────────┤
│John Doe │Late │05/15 │Resolved  │
│Jane Doe │Disc │04/20 │Pending   │
│...                              │
└──────────────────────────────────┘
```
**Fields:** Employee, violation type, date, status, notes
**Purpose:** Maintain disciplinary records for compliance

---

## 9. SECURITY CONSIDERATIONS

- **Authentication:** Laravel Breeze with secure password hashing and email verification
- **Authorization:** Role-based access control with permission checks enforced at middleware level
- **Data Protection:** HTTPS encryption in transit, password hashing at rest
- **Input Validation:** All user inputs validated server-side
- **CSRF Protection:** Laravel's CSRF tokens on all forms
- **SQL Injection Prevention:** Eloquent ORM with parameterized queries
- **XSS Prevention:** Blade template escaping
- **Audit Logging:** All user actions logged for compliance
- **Access Control:** Middleware-based route protection with role verification
- **Account Management:** Admin approval required before account activation

---

## 10. TESTING & QUALITY ASSURANCE

### Sample Test Accounts:

**Super Admin Account:**
- Email: `admin@hrms.ph`
- Password: `password`
- Access: Full system control

**Employee Test Accounts:**
- `juan.delacruz@hrms.ph` | Password: `password`
- `ana.santos@hrms.ph` | Password: `password`
- `carlo.reyes@hrms.ph` | Password: `password`

**Pending Account (for onboarding):**
- `bea.ramos@hrms.ph` | Password: `password` | Status: Pending

### Test Scenarios Covered:

1. **Authentication & Authorization**
   - ✓ User registration with email verification
   - ✓ Valid and invalid login attempts
   - ✓ Account status handling (active/pending/inactive)
   - ✓ Role-based access restrictions

2. **Employee Management**
   - ✓ CRUD operations for employees
   - ✓ Batch operations
   - ✓ Profile completion tracking
   - ✓ Soft delete and restore

3. **Attendance**
   - ✓ Clock in/out functionality
   - ✓ Hours calculation
   - ✓ Attendance record editing by admin

4. **Leave Management**
   - ✓ Leave request submission
   - ✓ Leave approval workflow
   - ✓ Leave balance validation

5. **Timesheets**
   - ✓ Timesheet submission
   - ✓ Admin approval/rejection
   - ✓ Batch assignment

6. **Performance & Violations**
   - ✓ Performance review creation
   - ✓ Violation recording
   - ✓ History tracking

---

## 11. DEPLOYMENT & MAINTENANCE

### Deployment Requirements:

- **Server OS:** Linux (Ubuntu 20.04+) or Windows Server 2019+
- **PHP:** 8.2+
- **MySQL:** 8.0+
- **Web Server:** Apache/Nginx
- **SSL Certificate:** HTTPS required for production

### Maintenance Tasks:

- Regular database backups
- Log file rotation
- Security updates for dependencies
- Performance monitoring
- User access reviews

---

## 12. FUTURE ENHANCEMENTS

Potential features for future development:

- Payroll integration and salary processing
- Benefits and allowance management
- Training and development tracking
- Recruitment and applicant tracking system
- Mobile native application
- Advanced analytics and reporting dashboards
- Email/SMS notification system
- Biometric attendance integration
- Third-party API integrations
- Multi-branch/multi-company support

---

## 13. CONCLUSION

The HrMs (Human Resources Management System) is a comprehensive, Laravel-based solution designed to modernize HR operations within organizations. By automating core HR processes—from employee onboarding through performance management—the system significantly reduces administrative overhead while improving data accuracy, compliance, and accessibility.

The system provides three distinct user experiences tailored to each role:
- **Super Admin:** Complete system control and user management
- **Sub Admin:** Operational HR management and approvals
- **Employees:** Self-service access to personal HR data and requests

With a robust technical foundation built on Laravel 12, comprehensive audit trails, role-based access control, and intuitive interfaces, HrMs is positioned to deliver immediate value and support organizational growth.

---

**Document Version:** 2.0 (Reformatted)
**Last Updated:** May 18, 2026
**Status:** Complete

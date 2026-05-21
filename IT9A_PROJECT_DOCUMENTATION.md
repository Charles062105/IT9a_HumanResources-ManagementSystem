# IT9A PROJECT DOCUMENTATION

## Human Resources Management System (HrMs)

---

**Project Title:** Human Resources Management System (HrMs)

**Course / Subject:** IT9A - Systems Development / Software Engineering Project

**Instructor:** [Insert Instructor Name]

**Student Name(s):** [Student Names]

**Section:** [Section Number]

**Date Submitted:** [Submission Date]

---

## I. Project Context

### A. Company Background

JCL Marketing was founded in 2015 as a small business in Davao City, Philippines—a trading company that distributes products. The company was established by its founder to deliver quality products and marketing services to local businesses and consumers, and has since become a well-known brand in the local distribution arena. Over the years, JCL Marketing has expanded its clientele and product lines, catering to various types of customers from individual buyers to medium-sized business establishments throughout the Davao Region.

The company is headquartered in Davao City and is privately owned and managed by its founder. JCL Marketing is organized into dedicated teams including Sales, Marketing, Warehouse, and Administrative staff who are all dedicated to the smooth delivery of merchandise and services. The management has instilled in the company a culture of discipline, teamwork, and customer first, which has been at the heart of the continued growth of the company and reputation it has in the market.

JCL Marketing is a firm in consumer goods distribution and marketing services with competitive price, timely delivery, and flexible payment options to clients. The company serves retailers, wholesalers, and institutional buyers, offering them a steady and reliable source of products. In the context of an expanding business, the implementation of an organized and efficient system to oversee employee performance is becoming equally critical, as it is to track attendance, manipulate payroll-related records, process leave requests, and maintain employee performance evaluations.

Understanding these challenges, JCL Marketing wanted to create a Human Resource Management System that met its requirements. The aim of the proposed HRMS is to digitize and automate all the HR-related tasks, minimize the chances of manual record keeping errors, and improve the efficiency of workforce management. Through this system, JCL Marketing will invest in its employees and create a more well-structured, transparent, and productive workplace for all.

#### Transactions of the Company

JCL Marketing operates through several key human resource transactions that are essential to the day-to-day management of its workforce. As a growing distribution and marketing company, the business handles the following HR-related processes on a regular basis:

**1. Employee Hiring and Onboarding**

When JCL Marketing hires a new employee, the HR officer collects the necessary personal information, employment documents, and government-issued identification numbers such as SSS, PAG-IBIG, and PhilHealth. The employee is then assigned to a specific department and position, given a designated work shift, and registered in the company records. This process is currently done manually through paper forms and logbooks, which makes retrieval and verification of records slow and prone to errors.

**2. Daily Attendance Monitoring**

JCL Marketing monitors the daily attendance of all employees to ensure proper workforce accountability. Employees are expected to report to work according to their assigned shift schedules. Currently, attendance is tracked using a manual logbook or timesheet where employees sign in and out. The HR officer then manually reviews these records at the end of each day or week to determine who was present, late, absent, or on leave. This manual process is time-consuming and susceptible to inconsistencies and missing entries.

**3. Leave Application and Approval**

Employees of JCL Marketing who need to take a leave of absence are required to submit a written leave request form to their immediate supervisor or the HR officer. The request specifies the type of leave, the dates covered, and the reason for the leave. The supervisor reviews and either approves or denies the request before forwarding the decision to the HR office for recording. Since this process is paper-based, tracking the status of pending requests and monitoring each employee's remaining leave balance is difficult and often leads to miscommunication between employees and management.

**4. Timesheet Recording and Verification**

At the end of each work week, employees at JCL Marketing are required to account for their total hours rendered, including any overtime work performed beyond regular working hours. The HR officer collects and manually verifies these timesheet records against the daily attendance logbook before endorsing them for payroll computation. Discrepancies between logged hours and actual attendance are difficult to detect without a centralized and automated tracking system, which sometimes results in payroll inaccuracies.

**5. Disciplinary Action and Violation Recording**

When an employee commits a workplace offense such as habitual tardiness, insubordination, or misconduct, the HR officer and management issue the appropriate disciplinary action. JCL Marketing follows a progressive discipline policy that begins with a verbal warning and escalates to a written warning, final warning, suspension, and ultimately termination depending on the severity and frequency of the offense. These disciplinary records are currently kept in physical folders, making it difficult to track an employee's complete violation history or retrieve records quickly when needed.

**6. Employee Performance Evaluation**

JCL Marketing conducts periodic performance evaluations to assess how well each employee is meeting their job responsibilities and contributing to the company's goals. Supervisors and the HR officer rate employees based on criteria such as productivity, attendance, attitude, and teamwork. The results are recorded and used as a basis for promotions, salary adjustments, and training recommendations. Currently, performance evaluations are conducted using printed forms, and the compiled results are stored in individual employee folders, making it challenging to generate performance summaries or compare records across departments.

### B. Problems

The following problems were identified that HRMS Pro is designed to address:

1. No automated attendance tracking, making manual monitoring error-prone and time-consuming.

2. Difficulty in tracking leave balances and approvals without a centralized system.

3. Manual timesheet submission leads to inconsistencies and delays in payroll processing.

4. Lack of a structured disciplinary management system, making it hard to track employee violations.

5. No formal digital system for conducting and recording performance reviews.

6. Absence of role-based access control, creating security and data privacy risks.

7. No audit trail for administrative actions, making it difficult to trace changes.

### C. Solutions

HRMS Pro addresses the identified problems with the following solutions:

1. An automated attendance module with clock in/out functionality, shift-based status computation, and overtime tracking.

2. A leave management module with a full approval workflow and automatic attendance integration upon approval.

3. A weekly timesheet submission and approval workflow with rejection feedback and resubmission capability.

4. A structured violation management module with an escalation path from Verbal Warning to Termination.

5. A performance review system with score-based auto-rating and written feedback for each employee.

6. Role-based access control through middleware ensuring Super Admin, Sub-Admin, and Employee data isolation.

7. A comprehensive audit log system that records all administrative actions with timestamps and IP addresses.

---

## II. Objective of the Study

This study aims to design and develop a Human Resources Management System for JCL Marketing that will automate and streamline the company's HR processes. Specifically, the study seeks to accomplish the following objectives:

1. To implement an employee attendance module that allows clock in/out functionality, computes attendance status (present or late) based on assigned work shifts and grace periods, and tracks overtime hours.

2. To develop a leave management module that handles leave request submissions, approval workflows, and automatic attendance record updates for approved leave dates.

3. To create a timesheet submission and approval system that enables employees to submit weekly timesheets and administrators to review, approve, or reject them with appropriate feedback.

4. To build a violation management module that records disciplinary actions following an escalation path from verbal warning to termination, with notifications sent to the concerned employee.

5. To design a performance review system that allows administrators to evaluate employee performance using a 1-10 scoring scale with auto-assigned ratings and written feedback.

6. To implement a role-based access control system that enforces data isolation among Super Admin, Sub-Admin, and Employee roles through middleware protection.

7. To generate audit logs for all administrative actions to maintain system transparency and security compliance.

---

## III. Scope and Limitations of the Study

### A. Scope

This study covers the design and development of a web-based Human Resources Management System for JCL Marketing. The system is intended to automate and digitize the core HR processes of JCL Marketing and specifically covers the following modules and functionalities:

1. Employee Registration, Profile Management, and Admin Approval Workflow

2. Daily Attendance Tracking with shift-based status computation and overtime monitoring

3. Leave Request Management with approval workflow and automatic attendance integration

4. Weekly Timesheet Submission and Approval with rejection feedback

5. Violation and Disciplinary Management with escalation path

6. Performance Review creation and employee viewing

7. Role-Based Access Control (Super Admin, Sub-Admin, Employee)

8. In-App Notifications for key system events

9. Audit Logging for all administrative actions

### B. Limitations

The following limitations define the boundaries of the current version of the Human Resources Management System for JCL Marketing:

1. The system does not include automated payroll computation, salary generation, or direct integration with external payroll software or banking systems. Approved timesheets serve only as payroll reference data.

2. The system does not support biometric devices, fingerprint scanners, or GPS-based attendance verification. Attendance is recorded manually by the employee through the web interface.

3. Mobile application development is not within the scope of this study. The HRMS is accessible only through a web browser on desktop or laptop computers.

4. The system does not include advanced HR analytics, workforce planning tools, or predictive reporting features. Reports are limited to basic attendance summaries and HR record views.

5. Integration with government online portals such as SSS, PhilHealth, and PAG-IBIG for automatic contribution computation and remittance is not included in this version.

6. The permissions and role_permissions tables in the database are designed as planned features for future development and are not fully functional in the current system version.

7. The system is designed specifically for the organizational structure and HR needs of JCL Marketing and may require further customization before it can be applied to other companies with different HR workflows.

8. The system assumes that all users have basic computer literacy and access to a stable internet connection. System performance may be affected by slow or unstable network environments.

---

## IV. Conceptual and Logical Design

### A. Business Rules

The following business rules govern the relationships and constraints within the HRMS:

1. A User can have one Employee profile. Each Employee profile is linked to exactly one User account.

2. An Employee belongs to exactly one Shift. A Shift can be assigned to many Employees.

3. An Employee can have many Attendance records. Each Attendance record belongs to exactly one Employee.

4. An Employee can submit many Leave requests. Each Leave request belongs to exactly one Employee.

5. An Employee can submit many Timesheets. Each Timesheet belongs to exactly one Employee.

6. An Employee can receive many Violations. Each Violation is linked to exactly one Employee and issued by one Admin User.

7. An Employee can have many Performance reviews. Each Performance review is linked to exactly one Employee and reviewed by one Admin User.

8. A User can receive many Notifications. Each Notification belongs to exactly one User.

9. An Admin (Sub-Admin or Super Admin) can approve many Leave requests and Timesheets.

10. A Super Admin can assign or change the roles of other users.

### B. System Workflows

The following workflows illustrate the key processes within the HRMS:

1. **Employee Registration and Onboarding Workflow** – New employees are registered, profiles are created, and access is provisioned.

2. **Daily Clock In/Out Attendance Workflow** – Employees record their attendance daily; the system computes status based on shift schedules.

3. **Leave Request and Approval Workflow** – Employees submit leave requests; supervisors approve or reject; attendance is updated accordingly.

4. **Timesheet Submission and Approval Workflow** – Employees submit weekly timesheets; administrators review and approve/reject with feedback.

5. **Violation Escalation Workflow** – Disciplinary actions are recorded and escalated following company policy.

6. **Performance Review Process Workflow** – Administrators conduct performance evaluations with scoring and feedback.

### C. Entity-Relationship Summary

| Entity | Relationships |
|--------|---------------|
| User | One-to-One with Employee |
| Employee | Many-to-One with Shift; One-to-Many with Attendance, Leave, Timesheet, Violation, Performance |
| Shift | One-to-Many with Employees |
| Violation | Many-to-One with Employee (recipient) and User (issuer) |
| Performance | Many-to-One with Employee (subject) and User (reviewer) |
| Notification | Many-to-One with User |
| Leave | Many-to-One with Employee; Many-to-One with User (approver) |
| Timesheet | Many-to-One with Employee; Many-to-One with User (approver) |

---

## V. Technical Specifications

### A. Technology Stack

| Component | Technology |
|-----------|------------|
| Backend Framework | Laravel 12 |
| Programming Language | PHP 8.2 |
| Frontend | Blade Templates, JavaScript |
| Styling | Tailwind CSS 4, Custom CSS |
| Database | MySQL |
| Authentication | Laravel Breeze |
| Architecture | MVC (Model-View-Controller) |

### B. Core Modules

| Module | Description |
|--------|-------------|
| Authentication | User login, registration, password management |
| Employee Management | Profile management, admin approval workflow |
| Attendance | Clock in/out, shift-based status, overtime tracking |
| Leave Management | Request submission, approval workflow, balance tracking |
| Timesheet | Weekly submission, approval with feedback |
| Violation Management | Disciplinary recording, escalation tracking |
| Performance Review | Score-based evaluation, feedback mechanism |
| Notifications | In-app notification system for key events |
| Audit Logs | Administrative action tracking with timestamps |

### C. User Roles

| Role | Description |
|------|-------------|
| Super Admin | Full system access, role assignment, all administrative functions |
| Sub-Admin | Department-level management, approval authority |
| Employee | Personal profile access, attendance, leave requests, timesheet submission |

---

## VI. Conclusion

The Human Resources Management System (HrMs) for JCL Marketing represents a comprehensive solution to address the challenges faced by the company in managing its workforce. By digitizing and automating core HR processes—including attendance tracking, leave management, timesheet processing, violation recording, and performance evaluations—the system will significantly reduce manual workload, minimize errors, and improve overall operational efficiency.

The implementation of role-based access control and audit logging ensures data security and transparency, while the user-friendly interface facilitates adoption across all levels of the organization. With its modular design, the system provides a solid foundation for future enhancements and scalability as JCL Marketing continues to grow.

---

*Document prepared for IT9A - Systems Development / Software Engineering Project*
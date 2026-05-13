<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Database-specific enum modifications
        $driver = DB::connection()->getDriverName();

        if ($driver === 'mysql') {
            // First, change role column to VARCHAR to allow data migration
            Schema::table('users', function (Blueprint $table) {
                DB::statement('ALTER TABLE users MODIFY role VARCHAR(255)');
            });

            // Convert existing 'admin' role to 'super_admin'
            DB::table('users')->where('role', 'admin')->update(['role' => 'super_admin']);

            // Now change back to ENUM with new values
            Schema::table('users', function (Blueprint $table) {
                DB::statement("ALTER TABLE users MODIFY role ENUM('super_admin', 'sub_admin', 'employee') DEFAULT 'employee'");
            });
        } elseif ($driver === 'sqlite') {
            // SQLite doesn't support ENUM natively, so just update the data
            DB::table('users')->where('role', 'admin')->update(['role' => 'super_admin']);
        }

        // Create permissions table
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., 'view_employees', 'edit_attendance'
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Create role_permissions pivot table
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('role'); // super_admin, sub_admin, employee
            $table->foreignId('permission_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['role', 'permission_id']);
        });

        // Seed permissions
        $this->seedPermissions();

        // Seed role_permissions
        $this->seedRolePermissions();
    }

    private function seedPermissions(): void
    {
        $permissions = [
            // Employee Management
            ['name' => 'view_employees', 'description' => 'View all employees'],
            ['name' => 'edit_employee', 'description' => 'Edit employee information'],
            ['name' => 'create_employee', 'description' => 'Create new employee'],
            ['name' => 'delete_employee', 'description' => 'Delete employee'],
            ['name' => 'toggle_admin_role', 'description' => 'Make employee admin or vice versa'],

            // Attendance
            ['name' => 'view_attendance', 'description' => 'View attendance records'],
            ['name' => 'edit_attendance', 'description' => 'Edit attendance records'],
            ['name' => 'mark_absent', 'description' => 'Run auto-absent function'],
            ['name' => 'view_attendance_reports', 'description' => 'View attendance reports'],

            // Timesheets
            ['name' => 'view_timesheets', 'description' => 'View timesheets'],
            ['name' => 'create_timesheet', 'description' => 'Create timesheets'],
            ['name' => 'approve_timesheet', 'description' => 'Approve timesheets'],
            ['name' => 'reject_timesheet', 'description' => 'Reject timesheets'],
            ['name' => 'view_timesheet_reports', 'description' => 'View timesheet reports'],

            // Leaves
            ['name' => 'view_leaves', 'description' => 'View leave requests'],
            ['name' => 'approve_leave', 'description' => 'Approve leave requests'],
            ['name' => 'reject_leave', 'description' => 'Reject leave requests'],
            ['name' => 'approve_long_leave', 'description' => 'Approve leaves > 7 days'],

            // Performance
            ['name' => 'view_performance', 'description' => 'View performance reviews'],
            ['name' => 'create_performance', 'description' => 'Create performance reviews'],
            ['name' => 'edit_performance', 'description' => 'Edit performance reviews'],

            // Violations
            ['name' => 'view_violations', 'description' => 'View violations'],
            ['name' => 'issue_violation', 'description' => 'Issue violations'],
            ['name' => 'issue_critical_violation', 'description' => 'Issue critical violations'],
            ['name' => 'delete_violation', 'description' => 'Delete violations'],

            // Notifications
            ['name' => 'send_notification', 'description' => 'Send notifications'],
            ['name' => 'broadcast_notification', 'description' => 'Broadcast to all employees'],

            // User & System
            ['name' => 'manage_users', 'description' => 'Create/delete users and approve registrations'],
            ['name' => 'view_audit_logs', 'description' => 'View audit logs'],
            ['name' => 'manage_system_settings', 'description' => 'Manage departments, positions, shifts'],
            ['name' => 'manage_sub_admins', 'description' => 'Create and manage sub-admins'],
        ];

        foreach ($permissions as $perm) {
            DB::table('permissions')->insert($perm);
        }
    }

    private function seedRolePermissions(): void
    {
        // Super Admin - all permissions
        $superAdminPerms = DB::table('permissions')->pluck('id')->toArray();
        foreach ($superAdminPerms as $permId) {
            DB::table('role_permissions')->insert([
                'role' => 'super_admin',
                'permission_id' => $permId,
            ]);
        }

        // Sub-Admin - limited permissions
        $subAdminPermNames = [
            'view_employees', 'edit_employee',
            'view_attendance', 'edit_attendance', 'mark_absent', 'view_attendance_reports',
            'view_timesheets', 'create_timesheet', 'approve_timesheet', 'reject_timesheet', 'view_timesheet_reports',
            'view_leaves', 'approve_leave', 'reject_leave',
            'view_performance', 'create_performance',
            'view_violations', 'issue_violation',
            'send_notification',
        ];
        $subAdminPerms = DB::table('permissions')->whereIn('name', $subAdminPermNames)->pluck('id')->toArray();
        foreach ($subAdminPerms as $permId) {
            DB::table('role_permissions')->insert([
                'role' => 'sub_admin',
                'permission_id' => $permId,
            ]);
        }

        // Employee - minimal permissions (handled via policies)
    }

    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
        Schema::dropIfExists('permissions');

        // Database-specific enum modifications
        $driver = DB::connection()->getDriverName();

        if ($driver === 'mysql') {
            // Convert super_admin and sub_admin back to admin before changing ENUM
            DB::table('users')->whereIn('role', ['super_admin', 'sub_admin'])->update(['role' => 'admin']);

            DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'employee') DEFAULT 'employee'");
        } elseif ($driver === 'sqlite') {
            // SQLite doesn't support ENUM natively, just update the data
            DB::table('users')->whereIn('role', ['super_admin', 'sub_admin'])->update(['role' => 'admin']);
        }
    }
};

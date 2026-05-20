<?php

use App\Http\Controllers\AssignedTimesheetController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeSetupController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PerformanceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TimesheetController;
use App\Http\Controllers\UserRequestController;
use App\Http\Controllers\ViolationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Employee profile setup (after account activation)
    Route::get('employees/setup', [EmployeeSetupController::class, 'show'])->name('employees.setup');
    Route::post('employees/setup', [EmployeeSetupController::class, 'store'])->name('employees.setup.store');

    // HRMS Resources (accessible by all authenticated users)
    Route::resource('attendance', AttendanceController::class);

    // Attendance time-in/out endpoints used by views
    Route::post('attendance/time-in', [AttendanceController::class, 'timeIn'])->name('attendance.time-in');
    Route::post('attendance/time-out', [AttendanceController::class, 'timeOut'])->name('attendance.time-out');
    Route::resource('leaves', LeaveController::class);
    Route::resource('timesheets', TimesheetController::class);
    Route::get('timesheets/my', [TimesheetController::class, 'my'])->name('timesheets.my');
    Route::resource('violations', ViolationController::class);
    Route::patch('violations/{violation}/resolve', [ViolationController::class, 'resolve'])->name('violations.resolve');
    Route::get('performance/my', [PerformanceController::class, 'my'])->name('performance.my');
    Route::resource('performance', PerformanceController::class);
    
    // Notification specific routes BEFORE resource (route order matters)
    Route::get('notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.read-all');
    Route::patch('notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::resource('notifications', NotificationController::class);

    // Leave approval actions used by dashboard/leaves views
    Route::middleware('admin')->group(function () {
        // Admin-only resource routes
        Route::resource('employees', EmployeeController::class);
        Route::patch('employees/{employee}/deactivate', [EmployeeController::class, 'deactivate'])->name('employees.deactivate');
        Route::patch('employees/{employee}/activate', [EmployeeController::class, 'activate'])->name('employees.activate');

        // Role management (super admin only)
        Route::patch('employees/{employee}/role', [EmployeeController::class, 'updateRole'])->name('employees.update-role')->middleware('super_admin');

        // Batch employee operations
        Route::post('employees/batch/deactivate', [EmployeeController::class, 'batchDeactivate'])->name('employees.batch-deactivate');
        Route::post('employees/batch/export', [EmployeeController::class, 'batchExport'])->name('employees.batch-export');

        Route::patch('leaves/{leave}/approve', [LeaveController::class, 'approve'])->name('leaves.approve');
        Route::patch('leaves/{leave}/deny', [LeaveController::class, 'deny'])->name('leaves.deny');
        Route::patch('timesheets/{timesheet}/approve', [TimesheetController::class, 'approve'])->name('timesheets.approve');
        Route::patch('timesheets/{timesheet}/reject', [TimesheetController::class, 'reject'])->name('timesheets.reject');

        Route::resource('assigned-timesheets', AssignedTimesheetController::class);

        // Admin-specific routes (both super_admin and sub_admin)
        // Explicit routes MUST come before the resource to avoid {request} capturing "X/approve"
        Route::patch('requests/{request_model}/approve', [UserRequestController::class, 'approve'])->name('requests.approve');
        Route::patch('requests/{request_model}/reject', [UserRequestController::class, 'reject'])->name('requests.reject');
        Route::resource('requests', UserRequestController::class);

        // Super Admin only - User role management
        Route::middleware('super_admin')->group(function () {
            Route::get('users/{user}/make-admin', [UserRequestController::class, 'makeAdmin'])->name('users.make-admin');
            Route::patch('users/{user}/assign-admin-role', [UserRequestController::class, 'assignAdminRole'])->name('users.assign-admin-role');
            Route::get('users/{user}/revoke-admin', [UserRequestController::class, 'showRevokeAdmin'])->name('users.revoke-admin-form');
            Route::patch('users/{user}/revoke-admin', [UserRequestController::class, 'revokeAdmin'])->name('users.revoke-admin');
        });
    });
});

require __DIR__.'/auth.php';

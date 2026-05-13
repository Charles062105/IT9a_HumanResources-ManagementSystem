<?php

namespace App\Providers;

use App\Models\Employee;
use App\Models\HrmsNotification;
use App\Models\Leave;
use App\Models\UserRequest;
use App\Models\Violation;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Paginator::defaultView('pagination::bootstrap-5');
        Paginator::defaultSimpleView('pagination::simple-bootstrap-5');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share sidebar badge counts to all views (runs once per request)
        View::composer('layouts.app', function () {
            if (! auth()->check()) {
                return;
            }

            $adminBadgeCount = 0;
            $pendingLeaves = 0;
            $openViolations = 0;
            $unreadNotifications = 0;
            $pendingRequests = 0;

            // Only query if user is admin
            if (auth()->user()->isAdmin()) {
                $adminBadgeCount = Employee::where('status', 'active')->count();
                $pendingLeaves = Leave::where('status', 'pending')->count();
                $openViolations = Violation::where('status', 'open')->count();
                $unreadNotifications = HrmsNotification::where('is_read', false)->count();
                $pendingRequests = UserRequest::where('status', 'pending')->count();
            }

            View::share('sidebarCounts', [
                'employeeCount' => $adminBadgeCount,
                'pendingLeaves' => $pendingLeaves,
                'openViolations' => $openViolations,
                'unreadNotifications' => $unreadNotifications,
                'pendingRequests' => $pendingRequests,
            ]);
        });
    }
}

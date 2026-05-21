<?php

namespace App\Providers;

use App\Helpers\DateHelper;
use App\Models\Employee;
use App\Models\HrmsNotification;
use App\Models\Leave;
use App\Models\UserRequest;
use App\Models\Violation;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
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
        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }

        // Set default timezone for Carbon to Asia/Manila (PHT)
        Carbon::setLocale('en');
        date_default_timezone_set('Asia/Manila');

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

            // Only query admin stats if user is admin
            if (auth()->user()->isAdmin()) {
                $adminBadgeCount = Employee::where('status', 'active')->count();
                $pendingLeaves = Leave::where('status', 'pending')->count();
                $openViolations = Violation::where('status', 'open')->count();
                $pendingRequests = UserRequest::where('status', 'pending')->count();
            }

            // Count unread notifications for current user (applies to all users)
            $unreadNotifications = HrmsNotification::where(function ($q) {
                $q->where('user_id', auth()->id())
                    ->orWhereNull('user_id');
            })->where('is_read', false)->count();

            View::share('sidebarCounts', [
                'employeeCount' => $adminBadgeCount,
                'pendingLeaves' => $pendingLeaves,
                'openViolations' => $openViolations,
                'unreadNotifications' => $unreadNotifications,
                'pendingRequests' => $pendingRequests,
            ]);
        });

        // Share DateHelper with all views
        View::share('DateHelper', DateHelper::class);
    }
}
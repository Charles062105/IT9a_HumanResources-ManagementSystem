<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckIncompleteProfile
{
    /**
     * Handle an incoming request.
     * Redirect users with incomplete profiles to the setup page.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only check for authenticated users who actually loaded a page (not API/AJAX calls)
        $user = Auth::user();
        if (! $user) {
            return $next($request);
        }

        // Skip routes that should never be blocked (prevents redirect loops)
        $routeName = $request->route()?->getName();
        if (in_array($routeName, [
            // Employee setup
            'employees.setup',
            'employees.setup.store',
            // Profile completion page/actions
            'profile.edit',
            'profile.update',
            'profile.destroy',
            // Auth/logout flows
            'logout',
        ], true)) {
            return $next($request);
        }

        // Admins do not need employee profile completion.
        // Check for both super_admin and sub_admin roles

        if ($user->role === 'super_admin' || $user->role === 'sub_admin') {
            return $next($request);
        }

        // Employees must have an employee record and completed profile.
        // If an employee record isn't present, send them to the setup form.
        if (! $user->employee || ! $user->employee->profile_completed) {
            return redirect()->route('employees.setup')
                ->with('info', 'Please complete your employee profile to continue.');
        }

        return $next($request);
    }
}

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
        // Skip this check for setup-related routes
        if ($request->routeIs('employees.setup', 'employees.setup.store')) {
            return $next($request);
        }

        // Skip for logout, auth, password, profile, and verification routes
        if ($request->routeIs('logout', 'password.*', 'profile.*', 'verification.*', 'auth.*')) {
            return $next($request);
        }

        $user = Auth::user();

        if ($user) {
            // Admins do not need employee profile completion.
            if ($user->role === 'admin') {
                return $next($request);
            }

            // Employees must have an employee record.
            if (!$user->employee) {
                return redirect()->route('employees.setup', ['user' => $user->id])
                    ->with('info', 'Please complete your employee profile to continue.');
            }

            // Employee record exists but is not marked complete.
            if (!$user->employee->profile_completed) {
                return redirect()->route('employees.setup', ['user' => $user->id])
                    ->with('info', 'Please complete your employee profile to continue.');
            }
        }

        return $next($request);
    }
}

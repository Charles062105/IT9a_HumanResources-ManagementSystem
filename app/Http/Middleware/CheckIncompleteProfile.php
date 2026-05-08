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

        // Skip for guest-only, auth, password, profile, and verification routes
        $path = $request->path();
        if (preg_match('/^(login|register|forgot-password|reset-password|verify-email|profile|logout|employees\/setup)/', $path)) {
            return $next($request);
        }

        // Admins do not need employee profile completion.
        if ($user->role === 'admin') {
            return $next($request);
        }

        // Employees must have an employee record and completed profile.
        if (! $user->employee || ! $user->employee->profile_completed) {
            return redirect()->route('employees.setup', ['user' => $user->id])
                ->with('info', 'Please complete your employee profile to continue.');
        }

        return $next($request);
    }
}

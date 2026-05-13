<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     * Only allow users whose role is exactly 'admin' to proceed.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            abort(401, 'Unauthenticated.');
        }

        if (Auth::user()->isAdmin()) {
            return $next($request);
        }

        abort(403, 'Unauthorized. Admin access required.');
    }
}

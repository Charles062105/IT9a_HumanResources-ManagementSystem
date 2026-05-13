<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminOnly
{
    /**
     * Handle an incoming request.
     * Only allow Super Admin users to proceed.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            abort(401, 'Unauthenticated.');
        }

        if (! auth()->user()->isSuperAdmin()) {
            abort(403, 'Unauthorized. Super Admin access required.');
        }

        return $next($request);
    }
}

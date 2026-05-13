<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     * Verify user has specific permission.
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (! auth()->check()) {
            abort(401, 'Unauthenticated.');
        }

        if (! auth()->user()->hasPermission($permission)) {
            abort(403, "Unauthorized. Permission '{$permission}' required.");
        }

        return $next($request);
    }
}

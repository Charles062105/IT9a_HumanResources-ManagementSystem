<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsEmployee
{
    /**
     * Handle an incoming request.
     * Only allow employee users to proceed.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role === 'employee') {
            return $next($request);
        }

        abort(403, 'Unauthorized. Employee access required.');
    }
}
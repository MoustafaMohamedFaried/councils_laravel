<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsSuperOrSystemAdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()->hasRole('Super Admin') || auth()->user()->hasRole('System Admin'))
            return $next($request);
        else
            abort(401); // Unauthorized
    }
}

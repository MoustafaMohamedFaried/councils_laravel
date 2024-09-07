<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsSuperAdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()->name == 'Super Admin' || auth()->user()->email == 'super@gmail.com')
            return $next($request);
        else
            abort(401); // Unauthorized
    }
}

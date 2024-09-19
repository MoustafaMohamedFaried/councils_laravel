<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsActiveMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $errorMessage = '';

        if (auth()->check() && auth()->user()->is_active == 1) {
            return $next($request);
        } elseif (auth()->check() && auth()->user()->is_active == 2) {
            $errorMessage = "You're account is pending to accept";
        } else {
            $errorMessage = "Your account is inactive. Please contact support.";
        }
        Auth::logout();

        // Optionally invalidate and regenerate session token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to the home page or an 'account inactive' page with a message
        return redirect()->route('login')->with('notActive', $errorMessage);
    }
}

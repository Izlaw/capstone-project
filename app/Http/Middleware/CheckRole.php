<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        //Check if user is authenticated, if not rediretc to login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Get the authenticated user's role
        $userRole = Auth::user()->role;

        //Check for user's role and if it doesn't match, redirect to acces-denied
        if ($userRole !== $role) {
            return redirect()->route('access-denied');
        }

        // If it matches, proceed with the request
        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (auth()->check() && auth()->user()->role == $role) {
            return $next($request);
        }

        // Redirect or abort if the user does not have the required role
        return redirect('/')->with('error', 'Unauthorized access');  // Or you can abort with 403
    }
}


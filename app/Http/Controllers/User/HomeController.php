<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // Get the authenticated user or null for guests

        if ($user) {
            // Check role for authenticated users
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'employee') {
                return redirect()->route('employee.dashboard');
            } elseif ($user->role === 'customer') {
                return view('customerui.home');
            } else {
                return redirect()->route('access-denied');
            }
        }

        // For guest users, return the home view
        return view('customerui.home');
    }
}

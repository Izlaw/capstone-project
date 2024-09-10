<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only(['username', 'password']);

        if (Auth::attempt($credentials)) {
            // Login successful, redirect to dashboard or whatever
            return redirect()->intended(route('dashboard'));
        } else {
            // Login failed, redirect back with error message
            return redirect()->back()->withErrors(['username' => 'Invalid credentials']);
        }
    }
}
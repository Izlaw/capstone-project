<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request) {
        // Validate the input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        // Attempt to log the user in
        if (Auth::attempt($credentials)) {
            $user = Auth::user(); // Get the logged-in user

            // Check if the user is an Admin or Employee and redirect accordingly
            if ($user->role === 'Admin') {
                return redirect()->route('admin.dashboard'); // Redirect to admin dashboard
            } elseif ($user->role === 'Employee') {
                return redirect()->route('employee.dashboard'); // Redirect to employee dashboard
            } else {
                return redirect()->intended(route('home')); // Redirect to home or other default page
            }
        } else {
            return redirect()->back()
                ->withErrors(['login' => 'Invalid email or password.'])
                ->withInput();
        }
    }

    public function edit(Request $request): View {
        return view('profile.edit', [
            'user' => $request->user(),  // Pass the authenticated user to the view
        ]);
    }

    /**
     * Handle the logout request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        // Log out the user
        Auth::logout();

        // Invalidate the session
        $request->session()->invalidate();

        // Regenerate the CSRF token
        $request->session()->regenerateToken();

        // Redirect to the login page
        return redirect('/login');
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Carbon\Carbon;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validation
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'sex' => ['required', 'string', 'in:male,female,other'],
            'bday' => [
                'required',
                'date',
                'before:' . Carbon::now()->subYears(18)->toDateString(),
            ],
            'contact' => ['required', 'string', 'max:11'],
            'address' => ['required', 'string', 'max:255'],
        ], [
            'bday.before' => 'You must be 18 years old or above!'
        ]);

        // Create a new user and save
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer', // Assuming 'customer' as default role
            'sex' => $request->sex,
            'bday' => $request->bday,
            'contact' => $request->contact,
            'address' => $request->address,
        ]);

        event(new Registered($user));

        // Remove to automatically log the user in after register
        // Auth::login($user);

        return redirect()->route('login');
    }
}

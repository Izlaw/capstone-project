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

    public function store(Request $request): RedirectResponse {
    try {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'sex' => ['required', 'string', 'in:male,female,other'],
            'bday' => ['required', 'date'],
            'contact' => ['required','string', 'max:11'],
            'address' => ['required', 'string', 'max:255'],
        ]);
        } catch (ValidationException $e) {
        return redirect()->back()->withErrors($e->validator)->withInput();
    }
    
    $user = new User();
    $user->name = $request->name;
    $user->email = $request->email;
    $user->password = Hash::make($request->password);
    $user->role = 'customer';
    $user->sex = $request->sex;
    $user->bday = $request->bday;
    $user->contact = $request->contact;
    $user->address = $request->address;

    $user->save();
    
    event(new Registered($user));
    Auth::login($user);
    return redirect()->route('login');
    
}
}

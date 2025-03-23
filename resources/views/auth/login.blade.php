<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @include('layouts.header')
</head>

<body class="bg-primary bg-cover overflow-y-hidden">
    <!-- Center container -->
    <div class="LoginContainer flex h-screen justify-center items-center bg-cover bg-center">
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Login Form -->
        <form method="POST" action="{{ route('login.post') }}" class="LoginForm bg-secondary p-8 rounded-lg shadow-2xl w-full max-w-md bg-opacity-90 backdrop-blur-lg">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" class="font-semibold text-highlight text-lg !text-highlight" />
                <x-text-input id="email" class="block mt-2 w-full border border-gray-300 p-3 rounded-md focus:outline-none focus:ring-2 focus:ring-highlight" type="email" name="email" :value="old('email')" required autofocus autocomplete="email" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger text-sm" />
            </div>

            <!-- Password -->
            <div class="mt-4 relative">
                <x-input-label for="passwordLogin" :value="__('Password')" class="font-semibold text-highlight text-lg !text-highlight" />
                <div class="relative">
                    <x-text-input id="passwordLogin"
                        class="block mt-2 w-full border border-gray-300 p-3 rounded-md focus:outline-none focus:ring-2 focus:ring-highlight pr-10"
                        type="password" name="password" required autocomplete="current-password" />
                    <button type="button" id="togglePasswordLogin" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hidden">
                        <!-- Eye off icon: visible when password is hidden -->
                        <svg id="eyeOffIconLogin" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.875 18.825A10.05 10.05 0 0110 19c-5 0-9-4-9-9 0-1.73.575-3.33 1.537-4.637M16.12 16.12A4.5 4.5 0 019.88 9.88m6.24 6.24L3 3" />
                        </svg>
                        <!-- Eye icon: hidden initially -->
                        <svg id="eyeIconLogin" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden"
                            viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 3C5 3 1.73 7.11 1 10c.73 2.89 4 7 9 7s8.27-4.11 9-7c-.73-2.89-5-7-9-7zm0 12a5 5 0 110-10 5 5 0 010 10z" />
                            <path d="M10 8a2 2 0 100 4 2 2 0 000-4z" />
                        </svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger text-sm" />
            </div>


            <!-- General Login Error -->
            @if ($errors->has('login'))
            <div class="mt-4 text-danger text-base font-semibold">
                {{ $errors->first('login') }}
            </div>
            @endif

            <!-- Remember Me -->
            <div class="RememberMeContainer block mt-6">
                <label for="remember_me" class="inline-flex items-center text-sm text-highlight">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-highlight shadow-sm focus:ring-highlight" name="remember">
                    <span class="ml-2">{{ __('Remember me') }}</span>
                </label>
            </div>

            <!-- Forgot password and login button -->
            <div class="flex items-center justify-between mt-6">
                @if (Route::has('password.request'))
                <a class="text-sm text-highlight hover:text-accent" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
                @endif

                <button class="font-semibold text-highlight hover:text-white hover:bg-accent transform transition duration-300 ease-in-out px-4 py-2 rounded-md shadow-lg focus:outline-none">
                    {{ __('Log in') }}
                </button>

            </div>

            <!-- Sign up button -->
            <div class="mt-6 text-center">
                <a href="{{ route('register') }}" class="text-sm font-semibold text-highlight hover:text-white hover:bg-accent transform transition duration-300 ease-in-out px-4 py-2 rounded-md shadow-lg focus:outline-none">
                    {{ __("Don't have an account? Sign up!") }}
                </a>
            </div>
        </form>
    </div>
</body>

</html>
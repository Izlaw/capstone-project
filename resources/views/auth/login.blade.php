<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../output.css" rel="stylesheet">
    <title>Login Page</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @include('layouts.header')
</head> 
<body class="bg-mainbackground bg-cover overflow-y-hidden">
</html>
<x-guest-layout>
    <!-- Center container -->
    <div class="flex h-screen justify-center items-center bg-mainbackground bg-cover bg-center">
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}" class="bg-maroonbgcolor p-6 rounded-lg shadow-lg w-full max-w-md">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" class="font-semibold"/>
                <x-text-input id="email" class="block mt-2 w-full border border-gray-300 p-2 rounded-md" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-sm" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" class="font-semibold"/>
                <x-text-input id="password" class="block mt-2 w-full border border-gray-300 p-2 rounded-md" type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-sm" />
            </div>

            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <!-- Forgot password and login button -->
            <div class="flex items-center justify-between mt-4">
                @if (Route::has('password.request'))
                    <a class="text-sm text-indigo-600 hover:text-indigo-900" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-primary-button class="ml-3 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md">
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
<body>
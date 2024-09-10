@vite('resources/css/app.css')
@vite('resources/js/app.js')
@include('layouts.header')
<body class="bg-mainbackground bg-cover overflow-y-hidden">

<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" class="mx-auto bg-brownbgcolor w-2/3 bg-opacity-80 backdrop-blur-md h-screen scroll-smooth snap-start snap-always">

    <p class="RegisterTxt translate-y-6 text-center text-4xl font-semibold text-white">Register</p>
    <p class="RegisterTxt translate-y-6 text-center text-lg font-medium text-white mb-10">Create an account with 7 GUYS House of Shop.</p>
        @csrf

        <div class="RegisterFormContainer overflow-y-scroll h-65percent">

            <!-- Name -->
            <div class="NameContainer">
                <x-text-input id="name" class="block w-11/12 mx-auto p-2 font-medium rounded" type="text" placeholder="Name:" name="name" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div class="Email Address Container">
                <x-text-input id="email" class="block w-11/12 mx-auto p-2 font-medium rounded mt-5" type="email" placeholder="Email:" name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>  

            <!-- Password -->
            <div class="PasswordContainer">
                <x-text-input id="password" class="block w-11/12 mx-auto p-2 font-medium rounded mt-5" type="password" placeholder="Password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-text-input id="password_confirmation" class="block w-11/12 mx-auto p-2 font-medium rounded mt-5" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <!-- Role -->
            <div class="RoleContainer block w-30percent mx-auto p-2 font-medium rounded">
                <select id="role" class="block mt-5 w-full rounded" name="role" required>
                    <option value="">Select Role</option>
                    <option value="admin">Admin</option>
                    <option value="customer">Customer</option>
                    <option value="employee">Employee</option>
                </select>
                <x-input-error :messages="$errors->get('role')" class="mt-2" />
            </div>

            <!-- Sex -->
            <div class="SexContainer block w-30percent mx-auto p-2 font-medium rounded">
                <select id="sex" class="block mt-5 mx-auto w-full" name="sex" required>
                    <option value="">Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
                <x-input-error :messages="$errors->get('sex')" class="mt-2" />
            </div>

            <!-- Birthday -->
            <div class="BirthdayContainer block w-30percent mx-auto p-2 font-medium rounded mt-5">
                <x-text-input id="bday" class="block mt-1 w-full" type="date" placeholder="Birthday" name="bday" :value="old('birthday')" required />
                <x-input-error :messages="$errors->get('birthday')" class="mt-2" />
            </div>

            <!-- Contact Number -->
            <div class="ContactNumContainer">
                <x-text-input id="contact" class="block w-11/12 mx-auto p-2 font-medium rounded mt-5" type="tel" placeholder="Contact Number" name="contact" :value="old('contact_number')" required />
                <x-input-error :messages="$errors->get('contact_number')" class="mt-2" />
            </div>

            <!-- Address -->
            <div class="AddressContainer">
                <x-text-input id="address" class="block w-11/12 mx-auto p-2 font-medium rounded mt-5" type="text" placeholder="Address" name="address" :value="old('address')" required />
                <x-input-error :messages="$errors->get('address')" class="mt-2" />
            </div>

            <x-primary-button class="mt-6 mx-auto block bg-maroonbgcolor text-white hover:text-maroonbgcolor hover:bg-white py-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>

        <!-- Register button -->
        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>
        </div>
    </form>
</x-guest-layout>

</body>
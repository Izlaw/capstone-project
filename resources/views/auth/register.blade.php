<title>Register Page</title>
@vite('resources/css/app.css')
@vite('resources/js/app.js')
@include('layouts.header')

<body class="bg-primary bg-cover overflow-y-hidden">

    <div class="RegisterFormContainer flex items-center justify-center h-screen">

        <form method="POST" action="{{ route('register') }}" class="mx-auto bg-secondary w-2/3 bg-opacity-80 backdrop-blur-md h-4/5 scroll-smooth snap-start snap-always rounded-md shadow-xl">

            <p class="RegisterTxt translate-y-6 text-center text-4xl font-semibold text-highlight">Register</p>
            <p class="RegisterTxt translate-y-6 text-center text-lg font-medium text-white mb-10">Create an account with 7 GUYS House of Shop.</p>
            @csrf

            <div class="RegisterFormContainer overflow-y-scroll h-65percent">

                <!-- First Name -->
                <div class="FirstNameContainer">
                    <label for="first_name" class="block w-11/12 mx-auto p-2 font-medium rounded text-highlight">First Name</label>
                    <x-text-input id="first_name" class="block w-11/12 mx-auto p-2 font-medium rounded focus:ring-highlight focus:border-highlight" type="text" placeholder="First Name" name="first_name" :value="old('first_name')" required autofocus autocomplete="given-name" />
                    <x-input-error :messages="$errors->get('first_name')" class="mt-2 text-red-500" />
                </div>

                <!-- Last Name -->
                <div class="LastNameContainer mt-4">
                    <label for="last_name" class="block w-11/12 mx-auto p-2 font-medium rounded text-highlight">Last Name</label>
                    <x-text-input id="last_name" class="block w-11/12 mx-auto p-2 font-medium rounded focus:ring-highlight focus:border-highlight" type="text" placeholder="Last Name" name="last_name" :value="old('last_name')" required autocomplete="family-name" />
                    <x-input-error :messages="$errors->get('last_name')" class="mt-2 text-red-500" />
                </div>

                <!-- Email Address -->
                <div class="Email Address Container">
                    <label for="email" class="block w-11/12 mx-auto p-2 font-medium rounded text-highlight mt-5">Email Address</label>
                    <x-input-error :messages="$errors->get('email')" class="block w-11/12 mx-auto text-red-500" />
                    <x-text-input id="email" class="block w-11/12 mx-auto p-2 font-medium rounded focus:ring-highlight focus:border-highlight" type="email" placeholder="Email:" name="email" :value="old('email')" required autocomplete="username" />
                </div>

                <!-- Password -->
                <div class="PasswordContainer relative">
                    <label for="passwordRegister" class="block w-11/12 mx-auto p-2 font-medium rounded text-highlight mt-5">
                        Password
                    </label>
                    <x-input-error :messages="$errors->get('password')" class="block w-11/12 mx-auto text-red-500" />
                    <div class="relative w-11/12 mx-auto">
                        <x-text-input id="passwordRegister"
                            class="block w-full p-2 font-medium rounded focus:ring-highlight focus:border-highlight pr-10"
                            type="password" placeholder="Password" name="password" required autocomplete="new-password" />
                        <button type="button" id="togglePasswordRegister"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hidden">
                            <!-- Eye off icon: visible when password is hidden -->
                            <svg id="eyeOffIconRegister" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0110 19c-5 0-9-4-9-9 0-1.73.575-3.33 1.537-4.637M16.12 16.12A4.5 4.5 0 019.88 9.88m6.24 6.24L3 3" />
                            </svg>
                            <!-- Eye icon: hidden initially -->
                            <svg id="eyeIconRegister" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 3C5 3 1.73 7.11 1 10c.73 2.89 4 7 9 7s8.27-4.11 9-7c-.73-2.89-5-7-9-7zm0 12a5 5 0 110-10 5 5 0 010 10z" />
                                <path d="M10 8a2 2 0 100 4 2 2 0 000-4z" />
                            </svg>
                        </button>
                    </div>
                </div>


                <!-- Confirm Password -->
                <div class="mt-4">
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-500" />
                    <x-text-input id="password_confirmation" class="block w-11/12 mx-auto p-2 font-medium rounded mt-5 focus:ring-highlight focus:border-highlight" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password" />
                </div>

                <!-- Sex and Birthday Container -->
                <div class="DisplaySidebySide flex justify-between mt-5">

                    <!-- Sex -->
                    <div class="SexContainer w-1/2 pr-2">
                        <label for="sex" class="block text-center text-sm font-medium text-highlight">Sex</label>
                        <select id="sex" class="block mx-auto w-1/2 mt-1 p-2 font-medium rounded focus:ring-highlight focus:border-highlight" name="sex" required>
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                        <x-input-error :messages="$errors->get('sex')" class="mt-2 text-red-500" />
                    </div>

                    <!-- Birthday -->
                    <div class="BirthdayContainer w-1/2 pl-2">
                        <label for="bday" class="block text-center text-sm font-medium text-highlight">Birthday</label>
                        <x-text-input id="bday"
                            class="block mx-auto w-1/2 mt-1 p-2 font-medium rounded focus:ring-highlight focus:border-highlight"
                            type="text"
                            placeholder="Select Birthday"
                            name="bday"
                            :value="old('birthday')"
                            required
                            readonly />
                        <x-input-error :messages="$errors->get('bday')" class="mt-2 text-red-500" />
                        <p class="text-xs text-center text-highlight mt-1">Must be 18 years or older</p>
                    </div>
                </div>

                <!-- Contact Number -->
                <div class="ContactNumContainer">
                    <label for="contact" class="block w-11/12 mx-auto p-2 font-medium rounded text-highlight mt-5">Contact Number</label>
                    <x-text-input id="contact" class="block w-11/12 mx-auto p-2 font-medium rounded focus:ring-highlight focus:border-highlight" type="tel" placeholder="Contact Number" name="contact" :value="old('contact_number')" required />
                    <x-input-error :messages="$errors->get('contact_number')" class="mt-2 text-red-500" />
                </div>

                <!-- Address -->
                <div class="AddressContainer">
                    <label for="address" class="block w-11/12 mx-auto p-2 font-medium rounded text-highlight mt-5">Address</label>
                    <x-text-input id="address" class="block w-11/12 mx-auto p-2 font-medium rounded focus:ring-highlight focus:border-highlight" type="text" placeholder="Address" name="address" :value="old('address')" required />
                    <x-input-error :messages="$errors->get('address')" class="mt-2 text-red-500" />
                </div>
            </div>

            <!-- Buttons wrapper - side by side -->
            <div class="flex justify-center items-center space-x-4 mt-6">
                <!-- Register button -->
                <button class="font-semibold text-highlight hover:text-white hover:bg-accent transform transition duration-300 ease-in-out px-4 py-2 rounded-md shadow-lg focus:outline-none">
                    {{ __('Register') }}
                </button>

                <!-- Login redirect -->
                <button type="button" class="font-semibold text-highlight hover:text-white hover:bg-accent transform transition duration-300 ease-in-out px-4 py-2 rounded-md shadow-lg focus:outline-none" onclick="window.location.href='{{ route('login') }}'">
                    {{ __('Already registered?') }}
                </button>
            </div>
        </form>
    </div>

</body>
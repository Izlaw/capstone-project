@vite('resources/css/app.css')
@vite('resources/js/app.js')
@include('layouts.header')
<body class="bg-mainbackground bg-cover overflow-y-hidden">

<div class="RegisterFormContainer flex items-center justify-center h-screen">

    <form method="POST" action="{{ route('register') }}" class="mx-auto bg-maroonbgcolor w-2/3 bg-opacity-80 backdrop-blur-md h-4/5 scroll-smooth snap-start snap-always rounded-md">

    <p class="RegisterTxt translate-y-6 text-center text-4xl font-semibold text-white">Register</p>
    <p class="RegisterTxt translate-y-6 text-center text-lg font-medium text-white mb-10">Create an account with 7 GUYS House of Shop.</p>
        @csrf

        <div class="RegisterFormContainer overflow-y-scroll h-65percent">

            <!-- Name -->
            <div class="NameContainer">
                <label for="name" class="block w-11/12 mx-auto p-2 font-medium rounded text-white">Name</label>
                <x-text-input id="name" class="block w-11/12 mx-auto p-2 font-medium rounded" type="text" placeholder="Name:" name="name" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div class="Email Address Container">
            <label for="name" class="block w-11/12 mx-auto p-2 font-medium rounded text-white mt-5">Email Address</label>
                <x-input-error :messages="$errors->get('email')" class="block w-11/12 mx-auto" />
                <x-text-input id="email" class="block w-11/12 mx-auto p-2 font-medium rounded" type="email" placeholder="Email:" name="email" :value="old('email')" required autocomplete="username" />
            </div>  

            <!-- Password -->
            <div class="PasswordContainer">
            <label for="name" class="block w-11/12 mx-auto p-2 font-medium rounded text-white mt-5">Password</label>
                <x-input-error :messages="$errors->get('password')" cjlass="block w-11/12 mx-auto" />
                <x-text-input id="password" class="block w-11/12 mx-auto p-2 font-medium rounded" type="password" placeholder="Password" name="password" required autocomplete="new-password" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                <x-text-input id="password_confirmation" class="block w-11/12 mx-auto p-2 font-medium rounded mt-5" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password" />
            </div>

            <!-- Sex and Birthday Container -->
            <div class="DisplaySidebySide flex justify-between mt-5">

                <!-- Sex -->
                <div class="SexContainer w-1/2 pr-2">
                <label for="sex" class="block text-center text-sm font-medium text-white">Sex</label>
                <select id="sex" class="block mx-auto w-1/2 mt-1 p-2 font-medium rounded" name="sex" required>
                        <option value="">Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                    <x-input-error :messages="$errors->get('sex')" class="mt-2" />
                </div>

                <!-- Birthday -->
                <div class="BirthdayContainer w-1/2 pl-2">
                    <label for="bday" class="block text-center text-sm font-medium text-white">Birthday</label>
                    <x-text-input id="bday" class="block mx-auto w-1/2 mt-1 p-2 font-medium rounded" type="date" placeholder="Birthday" name="bday" :value="old('birthday')" required />
                    <x-input-error :messages="$errors->get('birthday')" class="mt-2" />
                </div>
                
            </div>


            <!-- Contact Number -->
            <div class="ContactNumContainer">
            <label for="name" class="block w-11/12 mx-auto p-2 font-medium rounded text-white mt-5">Contact Number</label>
                <x-text-input id="contact" class="block w-11/12 mx-auto p-2 font-medium rounded" type="tel" placeholder="Contact Number" name="contact" :value="old('contact_number')" required />
                <x-input-error :messages="$errors->get('contact_number')" class="mt-2" />
            </div>

            <!-- Address -->
            <div class="AddressContainer">
            <label for="name" class="block w-11/12 mx-auto p-2 font-medium rounded text-white mt-5">Address</label>
                <x-text-input id="address" class="block w-11/12 mx-auto p-2 font-medium rounded" type="text" placeholder="Address" name="address" :value="old('address')" required />
                <x-input-error :messages="$errors->get('address')" class="mt-2" />
            </div>

            <!-- Register button -->
            <button class="mt-6 mx-auto block bg-maroonbgcolor p-2 px-4 rounded-lg text-white hover:text-maroonbgcolor hover:bg-white transition duration-300 ease-in-out">
                {{ __('Register') }}
            </button>
        </div>

        <!-- Login redirect -->
        <button type="button" class="LoginRedirectBtn text-white ml-2 mt-4 px-4 py-2 rounded-md hover:bg-white hover:text-maroonbgcolor transition duration-300 ease-in-out" onclick="window.location.href='{{ route('login') }}'">
            {{ __('Already registered?') }}
        <button>
    </form>
</div>

</body>
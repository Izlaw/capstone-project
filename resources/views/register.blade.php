<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @include('layouts.header')
</head>
<body class="bg-mainbackground bg-cover overflow-y-hidden">
    <!-- Register Container -->
    <div class="RegisterContainer mx-auto bg-brownbgcolor w-2/3 bg-opacity-80 backdrop-blur-md h-screen">
        <p class="RegisterTxt translate-y-6 text-center text-4xl font-semibold text-white">Register</p>
        <p class="RegisterTxt translate-y-6 text-center text-lg font-medium text-white mb-10">Create an account with 7 GUYS House of Shop.</p>

        <!-- Register Form Container -->
        <div class="RegisterFormContainer overflow-y-scroll h-65percent">

        <!-- Register Form -->
            <form class="RegisterForm scroll-smooth snap-start snap-always" action="{{ route('login')}}" method="post">
                @csrf
                <!-- Name -->
                <input type="text" id="name" class="block w-11/12 mx-auto p-2 font-medium rounded" placeholder="Name:" required>
                <!-- Username -->
                <input type="text" id="username" class="block w-11/12 mx-auto p-2 font-medium rounded mt-5" placeholder="Username:" required>
                <!-- Email -->
                <input type="email" id="email" class="block w-11/12 mx-auto p-2 font-medium rounded mt-5" placeholder="Email:" required>
                <!-- Password -->
                <input type="password" id="password" class="block w-11/12 mx-auto p-2 font-medium rounded mt-5" placeholder="Password:" required>
                <!-- Confirm Password -->
                <input type="password" id="confirmpassword" class="block w-11/12 mx-auto p-2 font-medium rounded mt-5" placeholder="Confirm Password:" required>
                <!-- Gender -->
                <input type="gender" id="gender" class="block w-11/12 mx-auto p-2 font-medium rounded mt-5" placeholder="Gender:" required>
                <!-- Birthday -->
                <input type="birthday" id="birthday" class="block w-11/12 mx-auto p-2 font-medium rounded mt-5" placeholder="Birthday:" required>
                <!-- Contact Number -->
                <input type="contactnum" id="contactnum" class="block w-11/12 mx-auto p-2 font-medium rounded mt-5" placeholder="Contact Number:" required>
                <!-- Address -->
                <input type="address" id="address" class="block w-11/12 mx-auto p-2 font-medium rounded mt-5" placeholder="Address:" required>

                <!-- Register button -->
                <button class="RegisterButton bg-maroonbgcolor text-white text-lg font-semibold p-2 rounded block mx-auto mt-5 w-2/4 hover:text-maroonbgcolor hover:bg-white">Register</button>            
            </form>
        </div>

        <!-- Already have an account? -->
        <p class="AlreadyHaveAccount text-lg font-medium mt-6 text-center">
            Already have an account? 
            <a href="{{ route('login')}}" class="text-white bg-maroonbgcolor rounded px-4 py-2 hover:text-maroonbgcolor hover:bg-white">
                Login
            </a>
        </p>
    </div>
</body>
</html>
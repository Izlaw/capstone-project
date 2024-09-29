<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Support</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @include('layouts.header')

</head>
<body class="bg-mainbackground bg-cover">
    
    <!-- FAQ Container -->
    <div class="FAQContainer mx-auto bg-brownbgcolor w-2/3 bg-opacity-80 backdrop-blur-md" style="height: calc(100vh - 48px);">
        <!-- Go back button -->
        <a href="{{ route('home') }}" class="GoBackButton absolute top-0.5 right-2 group">
            <img class="BackButton h-8 w-8 rounded-lg p-1 translate-y-2" src="../img/gobackbutton.svg">
        </a>
        <p class="FAQTxt ml-20 translate-y-8 text-8xl font-semibold">FAQs</p>
        <p class="FAQTxt ml-20 translate-y-8 text-lg font-medium mb-14">Frequently Asked Questions about<br>7 GUYS House of Shop.</p>

        <!-- FAQs -->
        
        <div class="QuestionContainer border-y-2 border-collapse border-black py-2 mb-[-2px]">
            <span class="Question text-lg font-semibold ml-2">FAQ Example</span>
            <span class="RevealAnswer text-xl font-bold absolute right-2 cursor-pointer">+</span>
            <div class="HiddenAnswer max-h-0 opacity-0 overflow-hidden transition-all duration-150 ease-in-out">This is the answer to the FAQ above.</div>
        </div>
        
        <div class="QuestionContainer border-y-2 border-collapse border-black py-2 mb-[-2px]">
            <span class="Question text-lg font-semibold ml-2">FAQ Example</span>
            <span class="RevealAnswer text-xl font-bold absolute right-2 cursor-pointer">+</span>
            <div class="HiddenAnswer max-h-0 opacity-0 overflow-hidden transition-all duration-150 ease-in-out">This is the answer to the FAQ above.</div>
        </div>
        
        <div class="QuestionContainer border-y-2 border-collapse border-black py-2 mb-[-2px]">
            <span class="Question text-lg font-semibold ml-2">FAQ Example</span>
            <span class="RevealAnswer text-xl font-bold absolute right-2 cursor-pointer">+</span>
            <div class="HiddenAnswer max-h-0 opacity-0 overflow-hidden transition-all duration-150 ease-in-out">This is the answer to the FAQ above.</div>
        </div>
        
        <div class="QuestionContainer border-y-2 border-collapse border-black py-2 mb-[-2px]">
            <span class="Question text-lg font-semibold ml-2">FAQ Example</span>
            <span class="RevealAnswer text-xl font-bold absolute right-2 cursor-pointer">+</span>
            <div class="HiddenAnswer max-h-0 opacity-0 overflow-hidden transition-all duration-150 ease-in-out">This is the answer to the FAQ above.</div>
        </div>
        
        <div class="QuestionContainer border-y-2 border-collapse border-black py-2 mb-[-2px]">
            <span class="Question text-lg font-semibold ml-2">FAQ Example</span>
            <span class="RevealAnswer text-xl font-bold absolute right-2 cursor-pointer">+</span>
            <div class="HiddenAnswer max-h-0 opacity-0 overflow-hidden transition-all duration-150 ease-in-out">This is the answer to the FAQ above.</div>
        </div>

        @php
            $randomEmployee = App\Models\User::where('role', 'employee')->inRandomOrder()->first();
        @endphp

        @if ($randomEmployee)
            <a href="{{ route('chat', ['recipient' => $randomEmployee->user_id]) }}" class="relative group w-32 block mt-14">
                <div class="MoreInfoContainer ml-2">
                    <p class="MoreInfo w-36 text-center font-semibold opacity-100 group-hover:opacity-0 transition-opacity duration-300 text-white bg-maroonbgcolor rounded p-2 absolute top-1/2 transform -translate-y-1/2">Still need help?</p>
                    <p class="MoreInfo w-36 text-center font-semibold opacity-0 group-hover:opacity-100 transition-opacity duration-300 text-maroonbgcolor bg-white rounded p-2 absolute top-1/2 transform -translate-y-1/2">Contact us!</p>
                </div>
            </a>
        @else
            <p>No employees available.</p>
        @endif

    </div>
</body>
</html>
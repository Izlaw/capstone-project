@props(['route'])

@php

@endphp

<a href="{{ $route }}" class="group flex items-center space-x-2 text-white transition-all duration-300 ease-in-out">
    <!-- Back Arrow Icon -->
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 transform group-hover:scale-125 group-hover:rotate-3 group-hover:translate-x-2 transition-all duration-300 ease-in-out" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
    </svg>
    <span class="text-lg font-semibold group-hover:text-white group-hover:opacity-0 transition-all duration-300 ease-in-out">
        Go Back
    </span>
</a>
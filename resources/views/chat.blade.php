<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-id" content="{{ Auth::id() }}">
    <meta name="conversation-id" content="{{ $convoID }}">
    <meta name="user-name" content="{{ Auth::user()->name }}">
    <meta name="pusher-app-key" content="{{ env('PUSHER_APP_KEY') }}">
    <meta name="pusher-cluster" content="{{ env('PUSHER_APP_CLUSTER') }}">
    @vite('resources/js/app.js')
    @vite('resources/js/chat.js')
    @vite('resources/css/app.css')
</head>

<body class="bg-primary bg-cover overflow-hidden">
    <div class="ChatContainer bg-secondary h-screen flex flex-col">
        <h1 class="TopBar text-highlight text-center w-full bg-deep text-2xl py-3 relative animate__animated animate__fadeIn">
            @php
            if (Auth::check()) {
            if (Auth::user()->role == 'customer') {
            $route = route('vieworder');
            } elseif (Auth::user()->role == 'employee') {
            $route = route('assistcustomer.index');
            } else {
            $route = route('home');
            }
            }
            @endphp

            <x-backbutton :route="$route" />
            <a href="{{ route('home') }}">
                <div class="flex flex-col justify-center ml-0">
                    <div class="weblogo1 text-lg font-bold text-highlight text-stroke-pink text-center">7 GUYS</div>
                    <div class="weblogo2 text-sm font-bold text-primary">HOUSE OF FASHION</div>
                </div>
            </a>
        </h1>

        <!-- ChatBody -->
        <div id="chat-container" class="flex flex-col h-screen overflow-y-auto">
            <ul id="messages" class="flex-1 overflow-y-auto flex flex-col p-4 space-y-4">
                <!-- Messages loaded dynamically -->
                @foreach ($messages as $message)
                <li class="flex {{ $message->user_id === Auth::id() ? 'text-right ml-auto' : 'text-left mr-auto' }} animate__animated animate__fadeIn animate__delay-1s">
                    <div class="flex items-start">
                        <div class="text-white">
                            <strong>{{ $message->user->name }} ({{ $message->messDate }})</strong><br>

                            <!-- Check if the message content is a valid image URL -->
                            @if (filter_var($message->messContent, FILTER_VALIDATE_URL) && strpos($message->messContent, '/storage/orders/qrcodes/') !== false)
                            <!-- Render image if the content is a valid URL pointing to the QR code -->
                            <img src="{{ asset($message->messContent) }}" alt="QR Code" class="max-w-xs mx-auto mt-2">
                            @else
                            <!-- Render regular text messages -->
                            <span class="bg-accent text-white p-2 rounded-lg inline-block">{{ $message->messContent }}</span>
                            @endif
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>

            <!-- Message input form -->
            <div class="p-4 flex items-center bg-accent">
                <form id="message-form" class="flex-grow">
                    <input type="text" id="message-input" class="w-full rounded-lg px-4 py-2 text-primary focus:outline-none focus:ring-2 focus:ring-highlight" placeholder="Type your message" autocomplete="off">
                </form>
                <!-- @if(Auth::check() && Auth::user()->isEmployee())
                <button id="employeeActionButton" class="bg-danger text-white rounded-lg px-4 py-2 ml-2 hover:bg-white hover:text-danger transition duration-300 ease-in-out transform hover:scale-105">
                    Terminate Session
                </button>
                @endif -->
            </div>
        </div>


    </div>
</body>

</html>
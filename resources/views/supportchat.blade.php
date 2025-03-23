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

<body class="bg-mainbackground bg-cover overflow-hidden">
    <div class="support-chat-container bg-brownbgcolor h-screen bg-opacity-80 backdrop-blur-md flex flex-col">
        <h1 class="top-bar text-highlight text-center w-full bg-deep text-2xl py-2 relative">
            <!-- Go back button -->
            <a href="{{ route('home') }}" class="go-back-button absolute top-0.5 right-2 group">
                <img class="back-button h-8 w-8 rounded-lg p-1 translate-y-2" src="/img/gobackbutton.svg" alt="Go Back">
            </a>
            You're talking wissth {{ $recipient->name }}
        </h1>

        <!-- Chat Body -->
        <div id="chat-container" class="flex flex-col h-screen overflow-y-auto">
            <ul id="messages" class="flex-1 overflow-y-auto flex flex-col p-4 bg-primary">
                @foreach ($messages as $message)
                <li class="mb-2 flex {{ $message->user_id === Auth::id() ? 'text-right ml-auto' : 'text-left mr-auto' }}">
                    <div>
                        <strong class="text-white">{{ $message->user->name }} ({{ $message->messDate }})</strong><br>
                        <span class="bg-secondary text-white p-2 rounded-lg inline-block">{{ $message->messContent }}</span>
                    </div>
                </li>
                @endforeach
            </ul>
            <div class="p-4 flex items-center bg-deep">
                <form id="message-form" class="flex-grow">
                    <input type="text" id="message-input" class="w-full rounded-lg" placeholder="Type your message" autocomplete="off">
                </form>
                @if(Auth::check() && Auth::user()->isEmployee())
                <button id="employeeActionButton" class="bg-primary text-white rounded-lg px-4 py-2 ml-2 hover:bg-white hover:text-maroonbgcolor transition duration-300 ease-in-out">
                    Terminate Session
                </button>
                @endif

            </div>
        </div>
    </div>
</body>

</html>
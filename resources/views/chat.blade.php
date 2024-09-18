<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-name" content="{{ Auth::user()->name }}">
    @vite('resources/js/app.js')
    @vite('resources/css/app.css')
</head>
<body class="bg-mainbackground bg-cover overflow-hidden">
    <!-- Chat -->
    <div class="ChatContainer bg-brownbgcolor h-screen bg-opacity-80 backdrop-blur-md flex flex-col">

        <!-- ChatHeader -->
        <h1 class="TopBar text-white text-center w-full bg-maroonbgcolor text-2xl py-2 relative">

            <!-- Go back button -->
        <a href="{{ Auth::check() ? (Auth::user()->isEmployee() ? route('assistcustomer') : route('customersupport')) : '#' }}" class="GoBackButton absolute top-0.5 right-2 group">
            <img class="BackButton h-8 w-8 rounded-lg p-1 translate-y-2" src="../img/gobackbutton.svg">
        </a>

            @php
            echo "You're talking with " . $recipient->name;
            @endphp
        </h1>

        <!-- ChatBody -->
        <div id="chat-container" class="flex flex-col h-screen overflow-y-auto">
            <ul id="messages" class="flex-1 overflow-y-auto flex flex-col p-4">
                <!-- Messages will be loaded here dynamically -->
            </ul>
            
            <form id="message-form" class="p-4">
                <input type="text" id="message-input" class="w-full rounded-lg" placeholder="Type your message">
            </form>
        </div>
        
    <script>
        window.currentUserId = {{ Auth::user()->user_id }};
    </script>

    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Support</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @include('layouts.customerheader')
    <link href="../output.css" rel="stylesheet">
    @include('layouts.header')
</head>
<body class="bg-mainbackground bg-cover overflow-y-hidden">
    <div class="Box h-screen w-70percent bg-maroonbgcolor block mx-auto">
        <div class="Header w-full bg-brownbgcolor">
            
        </div>
        test
    </div>

    <!-- Center the chat system part -->
    <div class="flex justify-center items-center min-h-screen bg-brown-800"> <!-- Added background here -->
        <!-- Chat system container with brown background -->
        <div class="relative z-10 w-full max-w-3xl bg-brown-500 rounded-lg shadow-lg flex flex-col">
            
            <!-- Chat Header (inside chat section, not the page header) -->
            <div class="bg-brown-700 p-4 rounded-t-lg flex justify-between items-center">
                <h2 class="text-lg font-semibold">Support Chat</h2>
                <span class="text-sm text-gray-400">Today</span>
            </div>

            <!-- Chat Body -->
            <div class="flex-1 h-[600px] overflow-y-auto p-6 space-y-4 bg-brown-700">
                
                <!-- Support (Left-aligned) -->
                <div class="flex items-start space-x-2">
                    <div class="flex-shrink-0">
                        <img src="../img/ces.jpg" alt="Support Avatar" class="w-10 h-10 rounded-full">
                    </div>
                    <div class="bg-gray-600 p-4 rounded-lg max-w-md">
                        <p><strong>Phrynces:</strong> Hi josh, how can I help you today?</p>
                        <p class="text-xs text-gray-400 mt-2">9:37 AM</p>
                    </div>
                </div>
                
                <!-- User (Right-aligned) -->
                <div class="flex items-start justify-end space-x-2">
                    <div class="bg-green-500 p-4 rounded-lg max-w-md">
                        <p>Hi Phrynces, I have issues with my card. Can you help out?</p>
                        <p class="text-xs text-gray-200 mt-2">9:38 AM</p>
                    </div>
                    <div class="flex-shrink-0">
                        <img src="../img/josh.jpg" alt="User Avatar" class="w-10 h-10 rounded-full">
                    </div>
                </div>

                <!-- Support (Left-aligned) -->
                <div class="flex items-start space-x-2">
                    <div class="flex-shrink-0">
                        <img src="../img/ces.jpg" alt="Support Avatar" class="w-10 h-10 rounded-full">
                    </div>
                    <div class="bg-gray-600 p-4 rounded-lg max-w-md">
                        <p><strong>Phrynces:</strong> Can you tell me more about your problem?</p>
                        <p class="text-xs text-gray-400 mt-2">9:38 AM</p>
                    </div>
                </div>

                <!-- User (Right-aligned) -->
                <div class="flex items-start justify-end space-x-2">
                    <div class="bg-green-500 p-4 rounded-lg max-w-md">
                        <p>Not really sure what’s going on, but I can't withdraw money.</p>
                        <p class="text-xs text-gray-200 mt-2">9:39 AM</p>
                    </div>
                    <div class="flex-shrink-0">
                        <img src="../img/josh.jpg" alt="User Avatar" class="w-10 h-10 rounded-full">
                    </div>
                </div>

            </div>

            <!-- Chat Input -->
            <div class="bg-brown-700 p-4 rounded-b-lg flex items-center">
                <input type="text" class="flex-1 bg-gray-700 p-3 rounded-full text-white focus:outline-none" placeholder="Type a message...">
                <button class="ml-4 bg-green-500 p-3 rounded-full hover:bg-green-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16l4-4m0 0l4-4m-4 4v8m0-8H4" />
                    </svg>
                </button>
            </div>

        </div>
    </div>
</body>

</html>

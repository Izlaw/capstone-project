<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Support</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @include('layouts.empheader')
</head>

<body class="bg-mainbackground bg-cover overflow-y-hidden">

<!-- ChatSupportContainer -->
<div class="ChatSupportContainer fixed inset-x-0 bottom-0 w-screen flex flex-col h-screen">

    <!-- Chat Header -->
    <div class="ChatHeaderContainer bg-maroonbgcolor w-full">
        <h2 class="text-lg font-semibold text-white text-center">Chat Support</h2>
    </div>

    <!-- ChatBodyContainer -->
    <div class="ChatBodyContainer bg-brownbgcolor flex flex-col flex-grow overflow-y-auto bg-opacity-80 backdrop-blur-md p-2">
        
        <!-- Support -->
        <div class="SupportContainer flex justify-start w-full py-2">
            <img src="../img/ces.jpg" alt="Support Avatar" class="SupportAvatar w-10 h-10 rounded-full pr-2">
            <div class="SupportInfoContainer">
                <p class="SupportName text-white">Support Name</p>
                <p class="SupportMsg text-white bg-maroonbgcolor p-2 rounded-full">Support message here</p>
            </div>
        </div>

        <!-- User -->
        <div class="CustomerContainer flex justify-end items-start w-full py-2">
            <div class="CustomerInfoContainer flex flex-col items-end">
                <p class="CustomerName text-white text-right pr-2">Customer Name</p>
                <p class="CustomerMsg text-white bg-maroonbgcolor p-2 rounded-full">Customer message here</p>
            </div>
            <img src="../img/josh.jpg" alt="Customer Avatar" class="CustomerAvatar w-10 h-10 rounded-full pr-2">
        </div>

        <!-- MessageContainer -->
        <div class="MessageContainer w-full flex justify-center mt-auto">
            <input type="text" class="MessageInput w-11/12 rounded-full text-black p-2" placeholder="Type a message..."> 
        </div>
    </div>
</div>

</body>
</html>

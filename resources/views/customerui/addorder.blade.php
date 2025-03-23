<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Order</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @include('layouts.header')
</head>

<body class="bg-primary overflow-hidden">
    <x-backbutton route="home" />

    <!-- Choices Container -->
    <div class="choicecontainer mx-auto p-6 flex flex-wrap justify-center gap-48 items-center min-h-[calc(100vh-150px)] pt-5 pb-16"> <!-- Increased gap here -->

        <!-- Add Custom Order Option -->
        <div id="addCustomOrder" class="AddOrderColumn w-full sm:w-1/2 md:w-1/3 lg:w-1/4 bg-accent p-4 relative group rounded-lg shadow-lg cursor-pointer">
            <img class="h-full w-full object-cover grayscale group-hover:grayscale-0 group-hover:scale-105 transition-transform duration-300 ease-in-out group-hover:border-2 group-hover:border-accent rounded-lg"
                src="../img/uploadorder.svg" alt="Add Custom Order">
            <div class="absolute inset-0 flex flex-col justify-center items-center opacity-100 transition-opacity duration-300 ease-in-out">
                <img class="h-32 w-32 mb-4 transition-transform duration-300 ease-in-out group-hover:scale-110"
                    src="../img/uploadbutton.svg" alt="Add Button">
                <!-- Added truncate and adjusted container width -->
                <span class="text-4xl text-white font-bold group-hover:scale-110 transition-transform duration-300 ease-in-out text-center truncate max-w-full"
                    style="-webkit-text-stroke: 2px black;">Add Custom <br>Order</span>
            </div>
        </div>

        <!-- View Collections Option -->
        <div class="ViewCollectionsColumn w-full sm:w-1/2 md:w-1/3 lg:w-1/4 bg-accent p-4 relative group rounded-lg shadow-lg">
            <a href="{{ route('viewcollections') }}" class="block h-full">
                <img class="h-full w-full object-cover grayscale group-hover:grayscale-0 group-hover:scale-105 transition-transform duration-300 ease-in-out group-hover:border-2 group-hover:border-accent rounded-lg"
                    src="../img/viewcollections.svg" alt="View Collections">
                <div class="absolute inset-0 flex flex-col justify-center items-center opacity-100 transition-opacity duration-300 ease-in-out">
                    <img class="h-32 w-32 mb-4 transition-transform duration-300 ease-in-out group-hover:scale-110"
                        src="../img/viewcollectionsbutton.svg" alt="View Collections Button">
                    <span class="text-4xl text-white font-bold group-hover:scale-110 transition-transform duration-300 ease-in-out text-center" style="-webkit-text-stroke: 2px black;">View Collections</span>
                </div>
            </a>
        </div>

    </div>

</body>

</html>
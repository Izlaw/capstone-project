<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @include('layouts.header')
</head>

<body class="bg-primary bg-cover overflow-hidden">

    <!-- Choices Container -->
    <div class="choicecontainer mx-auto p-6 flex flex-wrap justify-center gap-48 items-center min-h-[calc(100vh-150px)] pt-5 pb-16">

        <!-- Add Order Column -->
        <div class="AddOrderColumn w-full sm:w-1/2 md:w-1/3 lg:w-1/4 bg-accent p-4 relative group rounded-lg shadow-lg">
            <a href="{{ route('addorder') }}" class="block h-full">
                <img
                    class="h-full w-full object-cover grayscale group-hover:grayscale-0 group-hover:scale-110 transition-transform duration-300 ease-in-out group-hover:border-2 group-hover:border-accent rounded-lg"
                    src="../img/addorder.png" alt="Add Order">
                <div class="AddOrderContainer absolute inset-0 flex flex-col justify-center items-center opacity-100 transition-opacity duration-300 ease-in-out">
                    <img class="h-32 w-32 mb-4 transition-transform duration-300 ease-in-out group-hover:scale-110"
                        src="../img/addbutton.svg" alt="Add Button">
                    <span class="text-4xl text-white font-bold group-hover:scale-110 transition-transform duration-300 ease-in-out" style="-webkit-text-stroke: 2px black;">Add Order</span>
                </div>
            </a>
        </div>

        <!-- View Order Column -->
        <div class="ViewOrderColumn w-full sm:w-1/2 md:w-1/3 lg:w-1/4 bg-accent p-4 relative group rounded-lg shadow-lg">
            <a href="{{ route('vieworder') }}" class="block h-full">
                <img
                    class="h-full w-full object-cover grayscale group-hover:grayscale-0 group-hover:scale-110 transition-transform duration-300 ease-in-out group-hover:border-2 group-hover:border-accent rounded-lg"
                    src="../img/vieworder.png" alt="View Order">
                <div class="ViewOrderContainer absolute inset-0 flex flex-col justify-center items-center opacity-100 transition-opacity duration-300 ease-in-out">
                    <img class="h-32 w-32 mb-4 transition-transform duration-300 ease-in-out group-hover:scale-110"
                        src="../img/viewbutton.svg" alt="View Button">
                    <span class="text-4xl text-white font-bold group-hover:scale-110 transition-transform duration-300 ease-in-out" style="-webkit-text-stroke: 2px black;">View Order</span>
                </div>
            </a>
        </div>


    </div>

</body>

</html>
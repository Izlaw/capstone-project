<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @include('layouts.header')
</head>
<body class="bg-mainbackground bg-cover overflow-y-hidden">

    <!--Choices-->
    <div class="choicecontainer mx-auto p-4 flex justify-center scale-95" style="gap: 70px; height: calc(100vh - 48px)">
        <!--AddOrder-->
            <div class="AddOrderColumn w-1/2 bg-white p-2 relative group">
                <a href="{{ route('addorder') }}">
                    <img class="AddOrderPng h-full w-full grayscale group-hover:grayscale-0 group-hover:scale-110 transition duration-150 ease-in-out group-hover:border-8 group-hover:border-white" src="../img/addorder.png">                    
                    <div class="AddOrderContainer absolute inset-0 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-center opacity-0 group-hover:opacity-100 transition duration-150 ease-in-out">
                        <img class="AddOrderButtonPng mx-auto h-128 w-128 grayscale-0 hover:grayscale-0 transition duration-150 ease-in-out" src="../img/addbutton.svg">
                        <span class="AddOrderTxt text-center text-white text-104px" style="-webkit-text-stroke: 2px black;">Add Order</span>
                    </div>
                </a>
            </div>
        <!--ViewOrder-->
        <div class="ViewOrderColumn w-1/2 bg-white p-2 relative group">
            <a href="ViewOrder">
                <img class="ViewOrderPng h-full w-full grayscale group-hover:grayscale-0 group-hover:scale-110 transition duration-150 ease-in-out group-hover:border-8 group-hover:border-white" src="../img/vieworder.png">
                <div class="ViewOrderContainer absolute inset-0 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-center opacity-0 group-hover:opacity-100 transition duration-150 ease-in-out">
                    <img class="ViewOrderButtonPng mx-auto h-32 w-32 grayscale-0 hover:grayscale-0 transition duration-150 ease-in-out" src="../img/viewbutton.svg">
                    <span class="ViewOrderTxt text-center text-white text-104px" style="-webkit-text-stroke: 2px black;">View Order</span>
                </div>
            </a>
        </div>
    </div>
</body>
</html>
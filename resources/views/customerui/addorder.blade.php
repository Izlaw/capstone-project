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
<body class="bg-mainbackground bg-cover">

    <!--Choices-->
        <div class="ChoiceContainer mx-auto p-4 flex justify-center scale-95" style="gap: 70px; height: calc(100vh - 48px)">
        <!-- Go Back Option -->
            <div class="GoBackColumn w-1/2 bg-white p-2 relative group">
                <a href="{{ route('home')}}">
                    <img class="GoBackPng h-full w-full object-cover grayscale group-hover:grayscale-0 group-hover:scale-110 transition duration-150 ease-in-out group-hover:border-8 group-hover:border-white" src="../img/goback.png">                    
                    <div class="GoBackContainer absolute inset-0 0 flex flex-col justify-center items-center top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-center opacity-0 group-hover:opacity-100 transition duration-150 ease-in-out">
                        <img class="GoBackButtonSvg mx-auto h-128 w-128 grayscale-0 hover:grayscale-0 transition duration-150 ease-in-out" src="../img/gobackbutton.svg">
                        <span class="GoBackTxt text-center text-white" style="-webkit-text-stroke: 2px black; font-size: 104px;">Go Back</span>
                    </div>
                </a>
            </div>

            <!-- Add Order Option -->
            <div class="AddOrderColumn w-1/2 bg-white p-2 relative group">
                <a href="#" id="addOrderTrigger">
                    <img class="AddOrderSvg h-full w-full object-cover grayscale group-hover:grayscale-0 group-hover:scale-110 transition duration-150 ease-in-out group-hover:border-8 group-hover:border-white" src="../img/uploadorder.svg">                    
                    <div class="AddOrderContainer absolute inset-0 0 flex flex-col justify-center items-center top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-center opacity-0 group-hover:opacity-100 transition duration-150 ease-in-out">
                        <img class="AddOrderButtonSvg mx-auto h-128 w-128 grayscale-0 hover:grayscale-0 transition duration-150 ease-in-out" src="../img/uploadbutton.svg">
                        <span class="AddOrderTxt text-center text-white text-104px" style="-webkit-text-stroke: 2px black;">Add Order</span>
                    </div>
                </a>
            </div>

            <!-- View Collections Option -->
            <div class="ViewCollectionsColumn w-1/2 bg-white p-2 relative group">
                <a href="{{ route('viewcollections') }}">
                    <img class="ViewCollectionsSvg h-full w-full object-cover grayscale group-hover:grayscale-0 group-hover:scale-110 transition duration-150 ease-in-out group-hover:border-8 group-hover:border-white" src="../img/viewcollections.svg">                    
                    <div class="ViewCollectionsContainer absolute inset-0 0 flex flex-col justify-center items-center top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-center opacity-0 group-hover:opacity-100 transition duration-150 ease-in-out">
                        <img class="ViewCollectionsButtonSvg mx-auto h-128 w-128 grayscale-0 hover:grayscale-0 transition duration-150 ease-in-out" src="../img/viewcollectionsbutton.svg">
                        <span class="ViewCollectionsTxt text-center text-white text-80px" style="-webkit-text-stroke: 2px black;">View Collections</span>
                    </div>
                </a>
            </div>
        </div>

        <!-- Gender Selection Modal -->
        <div id="genderModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
            <div class="bg-maroonbgcolor p-6 h-17rem rounded-lg flex flex-col justify-center items-center text-center relative">
                <!-- Go Back button -->
                <a href="javascript:void(0);" onclick="hideGenderModal()" class="GoBackButton absolute top-2 right-2 group">
                    <img class="BackButton h-8 w-8 rounded-lg p-1" src="../img/gobackbutton.svg">
                </a>

                <p class="text-4xl font-semibold mb-10 text-white">Which gender?</p>
                <div class="flex space-x-8 mt-4 text-xl text-white">
                    <button id="maleOption" class="py-2 px-4 rounded hover:bg-white hover:text-black transition duration-200">Male</button>
                    <button id="femaleOption" class="py-2 px-4 rounded hover:bg-white hover:text-black transition duration-200">Female</button>
                </div>

                <!-- New Button at the Bottom -->
                <a href="{{ route('uploadorder') }}" class="mt-4 text-base text-white underline hover:text-gray-300">
                    Have an existing design? Upload here!
                </a>
            </div>
        </div>
    </body>
</html>
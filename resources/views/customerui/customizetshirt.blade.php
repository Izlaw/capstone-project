<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customize TShirt</title>
    @vite('resources/css/app.css') 
    @vite('resources/js/app.js')  
    @include('layouts.header')   
</head>
<body class="font-sans antialiased flex flex-col min-h-screen overflow-y-hidden">
    <div class="flex-grow">
        <!-- Page Content -->
        <main>
            <div class="flex flex-col items-center">
                <div id="tshirt-container" class="w-full h-96">
                    <!-- Tshirt Here -->
                </div>
            </div>
        </main>
    </div>

    <footer class="Footer p-2 bg-maroonbgcolor flex justify-between items-center">
        <!-- Go Back button -->
        <a href="{{ url()->previous() }}" class="GoBackButton group">
            <img class="BackButton h-8 w-8 rounded-lg p-1" src="../img/gobackbutton.svg" alt="Go Back">
        </a>

        <!-- Design container -->
        <div class="designContainer flex items-center justify-center flex-grow">

            <!-- Pick color -->
            <div id="colorPickerContainer" class="rounded"></div>

            <!-- Toggle spin -->
            <button id="toggleSpin" class="ml-2 border rounded p-2 bg-red-500">
                <img src="img/spin-icon.svg" class="h-8 w-8">
            </button>

            <!-- Reset camera -->
            <button id="resetCamera" class="ml-2 border rounded p-2 bg-black text-white">
                <img src="img/reset-icon.svg" class="h-8 w-8">
            </button>
            
            <!-- <button id="addDesign" class="ml-2 border rounded p-2 bg-blue-500 text-white">Add Design</button> -->
            <!-- <input type="file" id="uploadDesign" accept="image/*" class="ml-2" /> -->
        </div>
    </footer>

    @vite('resources/js/tshirt-customization.js') <!-- Load JavaScript specific to this page -->

</body>
</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Order Page</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @include('layouts.header')
</head>

<body class="bg-primary bg-cover overflow-y-hidden">

    <!-- Back Button -->
    <x-backbutton route="addorder" />

    <!-- Main Container with Adjusted Margin -->
    <div class="SelectionContainer mx-auto scale-95 bg-secondary rounded-lg shadow-xl -mt-4">

        <!-- Top Bar -->
        <div class="TopBar bg-deep text-white text-center flex justify-center items-center mt-2 h-16 relative rounded-t-lg shadow-md">
            <h2 class="text-white text-3xl font-semibold">Customize Your Order</h2>
        </div>

        <!-- Main Content -->
        <div class="flex h-[80vh] bg-opacity-90 p-4 rounded-lg">

            <!-- Options Box -->
            <div class="OptionsBox bg-deep text-white w-[300px] max-w-[280px] text-[28px] tracking-wide shadow-lg p-6 rounded-lg">
                <div class="SelectionChoices flex flex-col gap-y-5">
                    <a href="#TShirts" class="TShirts hover:bg-white hover:text-maroonbgcolor cursor-pointer px-4 py-2 rounded-md transition-all duration-300 ease-in-out transform hover:scale-105">
                        T-Shirts
                    </a>
                </div>
            </div>

            <!-- T-Shirt Options -->
            <div class="TShirtsOptionsOuter flex-1 overflow-x-auto max-w-full">
                <div class="TShirtsOptionPngsContainer flex flex-nowrap flex-shrink-0 max-w-[1200px] gap-4">

                    <!-- T-Shirt Option -->
                    <div class="OptionPngs bg-white w-[280px] h-[83vh] overflow-hidden rounded-lg shadow-lg transition-transform duration-300 ease-in-out hover:scale-105">
                        <a href="{{ route('customize', ['model' => 'tshirt']) }}">
                            <img src="/img/black-tshirt.png" alt="T-Shirt Model" class="h-full object-cover grayscale transition duration-500 ease-in-out transform hover:grayscale-0" />
                        </a>
                    </div>

                    <!-- Polo Option -->
                    <div class="OptionPngs bg-white w-[280px] h-[83vh] overflow-hidden rounded-lg shadow-lg transition-transform duration-300 ease-in-out hover:scale-105">
                        <a href="{{ route('customize', ['model' => 'polo']) }}">
                            <img src="/img/polo-tshirt.png" alt="Polo Model" class="h-full object-cover grayscale transition duration-500 ease-in-out transform hover:grayscale-0" />
                        </a>
                    </div>

                </div>
            </div>

        </div>

    </div>

</body>

</html>
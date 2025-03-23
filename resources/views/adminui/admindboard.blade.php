<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @include('layouts.header')
</head>

<body class="bg-primary bg-cover overflow-y-hidden">

    <!--Choices-->
    <div class="choicecontainer mx-auto p-4 flex justify-center scale-95" style="gap: 70px; height: calc(100vh - 48px)">

        <!-- Manage Order -->
        <div class="adminOrderColumn w-1/2 bg-accent p-6 relative group rounded-xl shadow-lg transition-transform hover:scale-105 transform flex flex-col">
            <a href="{{ route('adminOrder') }}" class="flex flex-col h-full">
                <div class="flex-grow flex items-center justify-center text-center opacity-100 transition duration-150 ease-in-out">
                    <span class="text-white text-4xl font-semibold py-10 rounded-xl transform scale-95 group-hover:scale-100 transition duration-300 w-full">
                        Manage Orders
                    </span>
                </div>
            </a>
        </div>

        <!-- Manage Collections -->
        <div class="adminCollectColumn w-1/2 bg-accent p-6 relative group rounded-xl shadow-lg transition-transform hover:scale-105 transform flex flex-col">
            <a href="{{ route('adminCollect') }}" class="flex flex-col h-full">
                <div class="flex-grow flex items-center justify-center text-center opacity-100 transition duration-150 ease-in-out">
                    <span class="text-white text-4xl font-semibold py-10 rounded-xl transform scale-95 group-hover:scale-100 transition duration-300 w-full">
                        Manage Collections
                    </span>
                </div>
            </a>
        </div>

        <!-- Manage Employees -->
        <div class="manageEmpColumn w-1/2 bg-accent p-6 relative group rounded-xl shadow-lg transition-transform hover:scale-105 transform flex flex-col">
            <a href="{{ route ('manageEmployees') }}" class="flex flex-col h-full">
                <div class="flex-grow flex items-center justify-center text-center opacity-100 transition duration-150 ease-in-out">
                    <span class="text-white text-4xl font-semibold py-10 rounded-xl transform scale-95 group-hover:scale-100 transition duration-300 w-full">
                        Manage Employees
                    </span>
                </div>
            </a>
        </div>

        <!-- Manage Prices -->
        <div class="managePricesColumn w-1/2 bg-accent p-6 relative group rounded-xl shadow-lg transition-transform hover:scale-105 transform flex flex-col">
            <a href="{{ route('adminPrices') }}" class="flex flex-col h-full">
                <div class="flex-grow flex items-center justify-center text-center opacity-100 transition duration-150 ease-in-out">
                    <span class="text-white text-4xl font-semibold py-10 rounded-xl transform scale-95 group-hover:scale-100 transition duration-300 w-full">
                        Manage Prices
                    </span>
                </div>
            </a>
        </div>

    </div>

</body>

</html>
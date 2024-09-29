<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Preview</title>
    @vite('resources/css/app.css') 
    @vite('resources/js/app.js')  
    @include('layouts.header')
</head>
<body class="bg-mainbackground bg-cover overflow-y-hidden">
    <div class="flex h-screen">
        <div class="w-1/4 bg-white shadow p-6 border-r-2 border-black">
            <h1 class="text-2xl font-bold text-center mb-4">Order Preview</h1>
            <h2 class="text-xl font-semibold mb-2">Order Details</h2>
            <p class="mb-2">
                <strong>Color:</strong> 
                <span class="inline-block w-4 h-4" style="background-color: {{ $order->color }};"></span>
                ({{ $order->color }})
            </p>
            <p class="mb-2"><strong>Collar Type:</strong> {{ $order->collar_type }}</p>
            <!-- Add more order details here as needed -->
        </div>

        <div class="flex-1">
            <canvas id="tshirtCanvas" class="w-full h-full" data-color="{{ $order->color }}"></canvas>
        </div>
</div>
    
    @vite('resources/js/tshirt-customization.js') 
</body>
</html>

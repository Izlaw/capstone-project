<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @include('layouts.header')
</head>

<body class="bg-gradient-to-r from-primary via-secondary to-accent bg-cover">
    <!-- Go back button -->
    <x-backbutton route="home" />
    <div class="orderDetailsContainer bg-opacity-90 p-8 rounded-lg shadow-lg max-w-screen-lg mx-auto">
        <!-- Header -->
        <div class="text-center text-white mb-8">
            <h1 class="text-4xl font-bold text-highlight">Order Details</h1>
        </div>
        <!-- Order Details Section -->
        <div class="mx-auto p-6 bg-white rounded-lg shadow-lg w-full max-w-4xl space-y-6">
            <div class="space-y-4">
                <p class="text-xl font-semibold text-secondary">Order Information</p>
                <p><strong>Total Price:</strong> ₱{{ number_format($order->orderTotal, 2) ?? 'N/A' }}</p>
                <p>
                    <strong>Order Status:</strong>
                    <span class="inline-block px-2 py-1 rounded-full 
                        @if($order->orderStatus === 'Pending') bg-yellow-400 text-black 
                        @elseif($order->orderStatus === 'In Progress') bg-blue-500 text-white 
                        @elseif($order->orderStatus === 'Ready for Pickup') bg-green-500 text-white 
                        @elseif($order->orderStatus === 'Completed') bg-gray-500 text-white 
                        @elseif($order->orderStatus === 'Cancelled') bg-red-500 text-white 
                        @else bg-gray-300 text-black 
                        @endif">
                        {{ $order->orderStatus ?? 'N/A' }}
                    </span>
                </p>
                <p><strong>Quantity:</strong> {{ $order->orderQuantity ?? 'N/A' }}</p>
                <p><strong>Date Ordered:</strong> {{ \Carbon\Carbon::parse($order->dateOrder)->format('M, j Y') }}</p>
                <p><strong>Date Received:</strong> {{ $order->dateReceived ? \Carbon\Carbon::parse($order->dateReceived)->format('M, j Y') : 'N/A' }}</p>
                <p><strong>Billing Statement:</strong>
                    @if ($fileExists)
                    <a href="{{ asset('storage/' . $billingFilePath) }}" target="_blank" class="text-deep hover:underline">
                        View Billing
                    </a>
                    @else
                    <span class="text-red-500">Billing statement not available.</span>
                    @endif
                </p>
            </div>
        </div>
        <!-- Order Type Section -->
        <div class="flex justify-center p-6">
            <div class="relative w-full max-w-4xl bg-white p-6 rounded-lg shadow-lg space-y-6">
                @if ($order->customID)
                <!-- Download QR Code Button at the top right with scale-up animation on hover -->
                <a href="{{ route('download.qrcode', $order->customOrder->customID) }}"
                    class="absolute top-4 right-4 bg-deep text-white px-3 py-1 rounded transform hover:scale-110 hover:bg-highlight transition duration-300">
                    Download QR Code
                </a>
                <div class="text-black p-4 rounded-md shadow-md">
                    <p class="text-lg font-semibold">Custom Order</p>
                    <p><strong>Custom Order Name:</strong> T-Shirt</p>
                    <p>
                        <strong>Colors:</strong>
                        @php
                        // Since the colors column is cast to an array, we can directly use it.
                        $colors = $order->customOrder->colors;
                        @endphp
                    <div class="flex space-x-4 mt-2">
                        @foreach($colors as $key => $color)
                        <div class="flex flex-col items-center">
                            <div class="w-6 h-6 rounded-full border" style="background-color: {{ $color }};"></div>
                            <span class="text-xs capitalize">{{ $key }}</span>
                        </div>
                        @endforeach
                    </div>
                    </p>
                    <div class="mt-4">
                        <p><strong>Sizes:</strong></p>
                        @if($order->customOrder->sizes->isNotEmpty())
                        <table class="min-w-full table-auto">
                            <thead>
                                <tr>
                                    <th class="px-2 py-1 text-left font-semibold">Size</th>
                                    <th class="px-2 py-1 text-left font-semibold">Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->customOrder->sizes as $size)
                                <tr>
                                    <td class="border px-2 py-1">{{ $size->sizeName }}</td>
                                    <td class="border px-2 py-1">{{ $size->pivot->quantity }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <p class="text-gray-500">N/A</p>
                        @endif
                    </div>
                    <p class="mt-4"><strong>Fabric Type:</strong> {{ ucfirst($order->customOrder->fabric_type) ?? 'N/A' }}</p>
                </div>
                @elseif ($order->collectID)
                <div class="text-black p-4 rounded-md shadow-md">
                    <p class="text-lg font-semibold">Collection Order</p>
                    <p><strong>Collection Name:</strong> {{ $order->collection->collectName ?? 'N/A' }}</p>
                    <p><strong>Sizes:</strong></p>
                    @if($order->collections->isNotEmpty())
                    <table class="min-w-full table-auto">
                        <thead>
                            <tr>
                                <th class="px-2 py-1 text-left font-semibold">Size</th>
                                <th class="px-2 py-1 text-left font-semibold">Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->collections as $collection)
                            <tr>
                                <td class="border px-2 py-1">{{ $collection->pivot->sizeName }}</td>
                                <td class="border px-2 py-1">{{ $collection->pivot->quantity }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <p class="text-gray-500">N/A</p>
                    @endif
                </div>
                @elseif ($order->upID)
                <div class="text-black p-4 rounded-md shadow-md">
                    <p class="text-lg font-semibold">Upload Order</p>
                    <p><strong>Upload Order Name:</strong> {{ ucfirst($order->uploadOrder->upName ?? 'N/A') }}</p>
                    <p><strong>Sizes:</strong></p>
                    @if($order->uploadOrder->sizes->isNotEmpty())
                    <table class="min-w-full table-auto">
                        <thead>
                            <tr>
                                <th class="px-2 py-1 text-left font-semibold">Size</th>
                                <th class="px-2 py-1 text-left font-semibold">Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->uploadOrder->sizes as $size)
                            <tr>
                                <td class="border px-2 py-1">{{ $size->sizeName }}</td>
                                <td class="border px-2 py-1">{{ $size->pivot->quantity }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <p class="text-gray-500">N/A</p>
                    @endif
                </div>
                @else
                <p class="text-lg font-semibold text-gray-500">Order Type: Unknown</p>
                @endif
            </div>
        </div>
    </div>
</body>

</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing Statement</title>
    @vite('resources/css/app.css') 
</head>
<body>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold text-center">Billing Statement</h1>
        <div class="mt-4">
            <h2 class="text-xl font-semibold">Information</h2>
            <p>Name: {{ $customerName }}</p>
            <p>Email: {{ $customerEmail }}</p>
            <p>Address: {{ $customerAddress }}</p>
        </div>

        <div class="mt-4">
            <h2 class="text-xl font-semibold">Order Summary</h2>
            <table class="min-w-full bg-white border border-gray-300">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b">Item</th>
                        <th class="py-2 px-4 border-b">Quantity</th>
                        <th class="py-2 px-4 border-b">Price</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="py-2 px-4 border-b">T-Shirt (XS)</td>
                        <td class="py-2 px-4 border-b">{{ $size_xs }}</td>
                        <td class="py-2 px-4 border-b">PHP {{ number_format($price_xs * $size_xs, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="py-2 px-4 border-b">T-Shirt (S)</td>
                        <td class="py-2 px-4 border-b">{{ $size_s }}</td>
                        <td class="py-2 px-4 border-b">PHP {{ number_format($price_s * $size_s, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="py-2 px-4 border-b">T-Shirt (M)</td>
                        <td class="py-2 px-4 border-b">{{ $size_m }}</td>
                        <td class="py-2 px-4 border-b">PHP {{ number_format($price_m * $size_m, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="py-2 px-4 border-b">T-Shirt (L)</td>
                        <td class="py-2 px-4 border-b">{{ $size_l }}</td>
                        <td class="py-2 px-4 border-b">PHP {{ number_format($price_l * $size_l, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="py-2 px-4 border-b">T-Shirt (XL)</td>
                        <td class="py-2 px-4 border-b">{{ $size_xl }}</td>
                        <td class="py-2 px-4 border-b">PHP {{ number_format($price_xl * $size_xl, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            <h2 class="text-xl font-semibold">Total Amount</h2>
            <p class="font-bold">PHP {{ number_format($totalAmount, 2) }}</p>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status Updated</title>
    <style>
        /* Ensure Tailwind CSS is compiled and available */
        @import 'tailwindcss/base';
        @import 'tailwindcss/components';
        @import 'tailwindcss/utilities';

        /* Custom email styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .email-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            background-color: #171c2f;
            color: #ffffff;
            padding: 20px;
            border-radius: 8px 8px 0 0;
        }
        .cta {
            display: inline-block;
            margin-top: 20px;
            background-color: #171c2f;
            color: #ffffff;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            text-align: center;
            transition: transform 0.3s ease-in-out;
        }
        .cta:hover {
            background-color: #faa183;
            color: #ffffff;
            transform: scale(1.05);
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
            color: #232f49;
        }
        .footer a {
            color: #faa183;
            text-decoration: none;
        }
        .footer a:hover {
            text-decoration: underline;
        }
        .status-indicator {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
        }
        .bg-yellow-400 {
            background-color: #facc15;
            color: #000;
        }
        .bg-blue-500 {
            background-color: #3b82f6;
            color: #fff;
        }
        .bg-green-500 {
            background-color: #10b981;
            color: #fff;
        }
        .bg-gray-500 {
            background-color: #6b7280;
            color: #fff;
        }
        .bg-red-500 {
            background-color: #ef4444;
            color: #fff;
        }
        .bg-gray-300 {
            background-color: #d1d5db;
            color: #000;
        }
    </style>
</head>
<body>
    @php
        // Determine the final order name based on IDs.
        if ($order->customID == 1) {
            $finalOrderName = 'T-Shirt';
        } elseif ($order->collectID == 1 && $order->collection) {
            $finalOrderName = $order->collection->collectName;
        } elseif ($order->upID == 1 && $order->uploadOrder) {
            $finalOrderName = $order->uploadOrder->upName;
        } else {
            $finalOrderName = $orderName;
        }
    @endphp

    <div class="email-container mx-auto bg-white p-6 rounded-lg shadow-md">
        <div class="header text-center bg-primary text-white p-4 rounded-t-lg">
            <h1 class="text-2xl font-semibold">Order Status Updated</h1>
        </div>
        <div class="content mt-6 text-gray-800">
            <p class="text-lg">Dear {{ $customerName }},</p>
            <p class="text-base mt-2">
                Your order <strong>"{{ $finalOrderName }}"</strong> status has been updated from 
                <span class="status-indicator @if($oldStatus === 'Pending') bg-yellow-400 @elseif($oldStatus === 'In Progress') bg-blue-500 @elseif($oldStatus === 'Ready for Pickup') bg-green-500 @elseif($oldStatus === 'Completed') bg-gray-500 @elseif($oldStatus === 'Cancelled') bg-red-500 @else bg-gray-300 @endif">
                    {{ $oldStatus }}
                </span>
                to
                <span class="status-indicator @if($newStatus === 'Pending') bg-yellow-400 @elseif($newStatus === 'In Progress') bg-blue-500 @elseif($newStatus === 'Ready for Pickup') bg-green-500 @elseif($newStatus === 'Completed') bg-gray-500 @elseif($newStatus === 'Cancelled') bg-red-500 @else bg-gray-300 @endif">
                    {{ $newStatus }}
                </span>.
            </p>
            <p class="mt-4">You can view your order details by clicking the button below:</p>
            <a href="{{ url('/orderdetails/' . $orderId) }}" class="cta inline-block mt-4">View Order Details</a>
            <p class="mt-4 text-sm text-gray-600">If you did not expect this update or have any questions, please contact our support team immediately.</p>
        </div>
        <div class="footer text-center mt-8 text-sm text-secondary">
            <p>Thank you for choosing 7GuysFashion!</p>
            <p>If you have any questions, feel free to <a href="mailto:support@7guysfashion.com" class="text-accent">contact us</a>.</p>
        </div>
    </div>
</body>
</html>

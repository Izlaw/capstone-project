<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Billing Statement</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7fafc;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }
        .header p {
            margin: 0;
            font-size: 14px;
            color: #718096;
        }
        .section {
            margin-bottom: 20px;
        }
        .section h2 {
            font-size: 20px;
            margin-bottom: 10px;
            font-weight: bold;
            color: #2d3748;
        }
        .section p {
            margin: 0;
            font-size: 16px;
            color: #4a5568;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th,
        .table td {
            border: 1px solid #e2e8f0;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #edf2f7;
            font-weight: bold;
            color: #2d3748;
        }
        .total {
            font-size: 18px;
            font-weight: bold;
            text-align: right;
            color: #2d3748;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #718096;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Company Information -->
        <div class="header">
            <h1>7 GUYS HOUSE OF FASHION</h1>
            <p>Burgos - Mabini - Plaza, 6 Burgos St, La Paz, Iloilo City, 5000 Iloilo</p>
            <p>Contact: +123 456 7890 | email@example.com</p>
        </div>

        <div class="section">
            <h2>Billing Statement</h2>
        </div>

        <!-- Customer Information -->
        <div class="section">
            <h2>Customer Information</h2>
            <p><strong>Name:</strong> {{ $firstName }} {{ $lastName }}</p>
            <p><strong>Address:</strong> {{ $customerAddress }}</p>
        </div>

        <!-- Order Information -->
        <div class="section">
            <h2>Order Information</h2>
            @isset($uploadOrder)
                <p><strong>Order Name:</strong> {{ $uploadOrder->upName }}</p>
            @else
                <p><strong>Order Name:</strong> T-Shirt</p>
            @endisset
            <p><strong>Order Date:</strong> {{ \Carbon\Carbon::parse($order->dateOrder)->format('M, j Y') }}</p>
        </div>

        <!-- Order Details -->
        <div class="section">
            <h2>Order Details</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Size</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sizes as $size)
                    <tr>
                        <!-- For collection orders, the size name may be stored as pivot_sizeName -->
                        <td>{{ isset($size->pivot_sizeName) ? $size->pivot_sizeName : $size->sizeName }}</td>
                        <!-- Use pivot->sizePrice if set, otherwise use sizePrice -->
                        <td>
                            @if(isset($size->pivot->sizePrice))
                                {{ number_format($size->pivot->sizePrice, 2) }}
                            @else
                                {{ number_format($size->sizePrice, 2) }}
                            @endif
                        </td>
                        <!-- For quantity, check both pivot->quantity and pivot_quantity -->
                        <td>{{ isset($size->pivot->quantity) ? $size->pivot->quantity : $size->pivot_quantity }}</td>
                        <!-- Calculate total -->
                        <td>
                            @php
                                $price = isset($size->pivot->sizePrice) ? $size->pivot->sizePrice : $size->sizePrice;
                                $quantity = isset($size->pivot->quantity) ? $size->pivot->quantity : $size->pivot_quantity;
                            @endphp
                            {{ number_format($price * $quantity, 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <p class="total">Total Amount: PHP{{ number_format($order->orderTotal, 2) }}</p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Thank you for your purchase!</p>
            <p>For inquiries, contact us at: +123 456 7890</p>
        </div>
    </div>
</body>
</html>

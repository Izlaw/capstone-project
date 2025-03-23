<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code and Billing Statement</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>

<body>
    <h1 class="text-center text-2xl font-bold mt-4">QR Code</h1>
    <div id="tshirtPreviewContainer" class="p-4">
        <!-- Display the QR Code -->
        <div id="qrCodeContainer" class="flex justify-center mt-4">
            <img src="{{ $qrCode }}" alt="QR Code">
        </div>

        <p class="mt-2 text-center text-secondary">Scan this QR code to view your customized design!</p>


        <!-- Download QR Code Link -->
        <div class="text-center mt-4">
            <a href="{{ route('download.qrcode', ['customID' => $order->customID]) }}"
                id="downloadQRCode"
                class="bg-primary text-white hover:bg-highlight transition-all font-bold duration-300 ease-in-out cursor-pointer py-2 px-4 rounded-lg shadow-md hover:scale-105">
                Download QR Code
            </a>
        </div>

        <!-- View Order -->
        <!-- <div class="text-center mt-4">
            <a href="{{ route('vieworder') }}" class="text-white group-hover:text-black mt-3">
                <span>View Order</span>
            </a>
        </div> -->
</body>

</html>
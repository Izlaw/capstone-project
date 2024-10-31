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
            {!! $qrCode !!} <!-- This will output the QR code as an SVG -->
        </div>
        <p class="mt-2 text-center">Scan this QR code to view your customized design!</p>

        <!-- Download the Billing Statement -->
        <button id="downloadBillingStatement" class="mt-4 text-blue-500 underline cursor-pointer">Download Billing Statement</button>
        </div>
    <script>
        // Pass data to the external JS file
        window.customizations = {!! json_encode($customizations ?? []) !!}; // Use an empty array if $customizations is not set
        </script>
</body>
</html>

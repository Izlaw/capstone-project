<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Order</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @include('layouts.header')
</head>

<body class="bg-primary bg-cover overflow-y-hidden">
    <!-- Go back button -->
    <x-backbutton route="home" />

    <div class="vieworderpgeContainer mx-auto bg-secondary w-full bg-opacity-80 backdrop-blur-lg rounded-2xl shadow-xl p-10">
        <div>
            @livewire('order-list')
        </div>
    </div>
</body>

</html>
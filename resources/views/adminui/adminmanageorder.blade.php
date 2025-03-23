<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    @vite([
    'resources/js/app.js',
    'resources/css/app.css',
    ])
    @include('layouts.header')
</head>

<body class="bg-primary bg-cover overflow-y-hidden">

    <!-- Go back button -->
    <x-backbutton route="home" />

    <div class="mx-auto bg-secondary w-full shadow-xl p-10">
        <!-- Order details -->
        <div>
            @livewire('order-list', ['orders' => $orders])
        </div>
    </div>

    @vite('resources/js/manageorder.js') <!-- Move manageorder.js here -->

</body>

</html>
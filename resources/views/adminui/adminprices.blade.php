<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Size Prices</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @include('layouts.header')

    <!-- Include Livewire styles -->
    <livewire:styles />
</head>

<body class="bg-primary overflow-y-hidden">

    <!-- Back Button -->
    <x-backbutton route="home" />


    <!-- Content Container -->
    <div class="mx-auto bg-secondary w-full p-10">
        <h1 class="text-4xl text-highlight font-extrabold mb-6">Manage Sizes</h1>

        <!-- Table Wrapper -->
        <div class="overflow-x-auto w-full">
            <!-- Livewire Component (Manage Sizes Table) -->
            @livewire('manage-sizes')
        </div>

    </div>

    <!-- Include Livewire scripts -->
    <livewire:scripts />
</body>

</html>
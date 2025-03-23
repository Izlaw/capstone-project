<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Collections</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @vite('resources/js/managecollection.js')
    @vite('resources/js/swalpopup.js')
    @include('layouts.header')
</head>

<body class="bg-primary bg-cover overflow-y-hidden">

    <!-- Go back button -->
    <x-backbutton route="home" />

    @livewire('manage-collections')

</body>

</html>
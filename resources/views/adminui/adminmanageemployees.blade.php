<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Staff</title>
    @vite([
    'resources/js/app.js',
    'resources/css/app.css',
    ])
    @include('layouts.header')
</head>

<body class="bg-primary bg-cover">
    <x-backbutton route="home" />

    <div class="Empcontainer mx-auto p-5">
        @livewire('manage-employees')
    </div>

    @vite('resources/js/manageemployee.js')
</body>

</html>
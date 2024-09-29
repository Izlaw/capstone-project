<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @include('layouts.header')
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen ">
          <!-- Include your header here -->

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>
    </div>
</body>
</html>

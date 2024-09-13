<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @include('layouts.header')
    @include('profile.edit')
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
          <!-- Include your header here -->

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>
    </div>
</body>
</html>

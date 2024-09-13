<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="max-w-md mx-auto p-6 bg-white shadow-md rounded">
        <h1 class="text-xl font-bold mb-4">Access Denied</h1>
        <p class="mb-4">You do not have permission to access this page.</p>
        <a href="{{ route('home') }}" class="text-blue-500">Return to Home</a>
    </div>
</body>
</html>

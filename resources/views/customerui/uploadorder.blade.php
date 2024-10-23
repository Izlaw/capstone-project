<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webpage Title</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @include('layouts.header')
</head>
<body class="bg-mainbackground bg-cover overflow-y-hidden">
    <!-- Back Button -->
<a href="{{ route('addorder')}}" class="GoBackButton absolute group">
    <img class="BackButton h-8 w-8 rounded-lg p-1" src="../img/gobackbutton.svg">
    <h2>UPLOAD ORDER</h2>
</a>


    
</body>
</html>
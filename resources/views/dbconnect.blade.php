<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../output.css" rel="stylesheet">
    <title>Webpage Title</title>
    @vite('resources/css/app.css')
    @include('layouts.header')
    @include('layouts.header')
</head>
<body class="bg-mainbackground bg-cover">
    <div>
        @if(DB::connection()->getPdo())
            Successfully connected to DB and DB name is {{ DB::connection()->getDatabaseName() }}
        @endif
    </div>
</body>
</html>
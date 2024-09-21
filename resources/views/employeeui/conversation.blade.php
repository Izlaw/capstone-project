<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conversation Details</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @include('layouts.empheader')
</head>
<body>
    <h1 class="text-center text-2xl">Conversation with {{ $conversationUser->name }}</h1>
    
    <div class="container mx-auto p-4">
        @foreach($messages as $message)
            <div class="mb-4">
                <strong>{{ $message->user->name }}:</strong>
                <p>{{ $message->content }}</p>
            </div>
        @endforeach
    </div>
</body>
</html>

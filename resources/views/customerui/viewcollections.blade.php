<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Collections</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @vite('resources/js/collectionorder.js')
    @include('layouts.header')
</head>

<body class="bg-primary bg-cover overflow-hidden">

    <!-- Back Button -->
    <x-backbutton route="addorder" />

    <div class="containerCollections mx-auto p-4">
        <h1 class="text-4xl font-bold mb-8 text-center text-white">COLLECTIONS</h1>

        <!-- New Parent Div for Flexbox -->
        <div class="flex flex-wrap justify-center gap-48" id="collectionsContainer">
            @foreach($collections as $collection)
            <div id="collection-{{ $collection->collectID }}" class="collection-item bg-accent p-4 relative group rounded-lg shadow-lg w-full sm:w-1/2 md:w-1/3 lg:w-1/4 cursor-pointer"
                data-collection='@json($collection)'>
                <div class="block h-full">
                    <img
                        class="h-full w-full object-cover grayscale group-hover:grayscale-0 group-hover:scale-110 transition-transform duration-300 ease-in-out group-hover:border-2 group-hover:border-accent rounded-lg"
                        src="{{ asset('storage/' . $collection->collectFilePath) }}" alt="{{ $collection->collectName }}"> <!-- Dynamic image path -->
                    <div class="absolute inset-0 flex flex-col justify-center items-center opacity-100 transition-opacity duration-300 ease-in-out">
                        <h2 class="text-xl font-semibold text-white group-hover:scale-110 transition-transform duration-300 ease-in-out text-shadow-xl">{{ $collection->collectName }}</h2>
                        <p class="text-lg text-white group-hover:scale-110 transition-transform duration-300 ease-in-out text-shadow-xl">Price: ₱{{ number_format($collection->collectPrice, 2) }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</body>

</html>
<div class="containerCollections mx-auto bg-secondary w-full shadow-xl p-10">
    <h1 class="text-4xl text-highlight font-extrabold mb-6 text-center">Manage Collections</h1>

    <div class="flex flex-wrap justify-center gap-8">
        @foreach($collections as $collection)
        <div class="bg-white bg-opacity-80 p-4 rounded-lg shadow-lg w-full sm:w-1/2 md:w-1/3 lg:w-1/4">
            <h2 class="text-xl font-semibold text-center">{{ $collection->collectName }}</h2>
            <img src="{{ asset('storage/' . $collection->collectFilePath) }}" class="w-full h-48 object-cover rounded-lg mt-4">

            <!-- Details Container -->
            <div class="bg-accent bg-opacity-80 p-2 rounded-lg mt-4 text-white">
                <h3 class="text-lg font-semibold">{{ $collection->collectName }}</h3>
                <p>Price: ₱{{ number_format($collection->collectPrice, 2) }}</p>
            </div>

            <!-- Managing Orders-->
            <div class="button-container mt-4 flex justify-center gap-4">
                <button class="edit-button bg-green-500 text-white py-2 px-4 rounded hover:bg-green-700" wire:click="showEditCollectionPopup({{ $collection->collectID }}, '{{ $collection->collectName }}', {{ $collection->collectPrice }}, '{{ $collection->collectFilePath }}')">Edit</button>
                <button class="delete-button bg-red-500 text-white py-2 px-4 rounded hover:bg-red-700" wire:click="showDeleteCollectionPopup({{ $collection->collectID }})">Delete</button>
            </div>
        </div>
        @endforeach

        <!-- Placeholder for Create Collection -->
        <div class="bg-white bg-opacity-80 p-4 rounded-lg shadow-lg w-full sm:w-1/2 md:w-1/3 lg:w-1/4 flex flex-col justify-center items-center cursor-pointer" wire:click="showCreateCollectionPopup">
            <div class="flex flex-col justify-center items-center h-full">
                <h2 class="text-xl font-semibold text-center text-gray-400">Create Collection</h2>
                <div class="w-full h-48 flex justify-center items-center bg-gray-200 rounded-lg mt-4">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <div class="bg-accent bg-opacity-80 p-2 rounded-lg mt-4 w-full text-center">
                    <h3 class="text-lg font-semibold text-gray-400">Collection Name</h3>
                    <p class="text-gray-400">Price</p>
                </div>
            </div>
        </div>
    </div>
</div>
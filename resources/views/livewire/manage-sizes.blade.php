<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<div class="h-[calc(100vh-13rem)] overflow-y-auto">
    <table class="table-auto rounded-lg text-center text-white w-full">
        <thead class="sticky top-0 bg-primary text-highlight z-10">
            <tr class="bg-primary text-highlight">
                <th class="py-4 px-6 text-lg font-semibold">Size ID</th>
                <th class="py-4 px-6 text-lg font-semibold">Name</th>
                <th class="py-4 px-6 text-lg font-semibold">Price</th>
                <th class="py-4 px-6 text-lg font-semibold">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sizes as $sizeID => $size)
            <tr class="bg-white text-black border-b border-highlight hover:bg-accent hover:text-black transition-all duration-200 ease-in-out group">
                <td class="py-4 px-6">{{ $sizeID }}</td> <!-- Directly access sizeID -->
                <td class="py-4 px-6">{{ $size['sizeName'] }}</td> <!-- Access array value for sizeName -->

                <td class="py-4 px-6">
                    <!-- Use dynamic wire:model for each price input and set current price as placeholder -->
                    <input
                        type="text"
                        wire:model="sizes.{{ $sizeID }}.price"
                        class="text-center bg-accent border border-highlight rounded-lg py-2 px-4 placeholder-white group-hover:bg-white group-hover:placeholder-black placeholder-opacity-100 focus:placeholder-gray-500"
                        placeholder="{{ $size['sizePrice'] }}"
                        wire:keydown.enter="updatePrice({{ $sizeID }})">

                </td>

                <td class="py-4 px-6">
                    <!-- Save Button that will call the updatePrice method -->
                    <button
                        class="bg-highlight text-white py-2 px-4 rounded-lg transform transition-all duration-300 ease-in-out
            hover:scale-110 hover:bg-primary hover:text-white focus:outline-none"
                        wire:click="updatePrice({{ $sizeID }})">
                        <!-- Save Icon from Bootstrap Icons -->
                        <i class="bi bi-save"></i> <!-- Add save icon here -->
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>

    </table>
</div>
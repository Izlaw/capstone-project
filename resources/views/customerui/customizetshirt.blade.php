<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customize TShirt</title>
    @vite('resources/css/app.css') 
    @vite('resources/js/app.js')  
    @include('layouts.header')   
</head>
<body class="font-sans antialiased flex flex-col min-h-screen overflow-y-hidden">
    <div class="flex-grow">
        <!-- Page Content -->
        <main>
            <div class="flex flex-col items-center">
                <div id="tshirt-container" class="w-full h-96">
                    <!-- Tshirt Here -->
                </div>
            </div>
        </main>
    </div>

    <footer class="Footer p-2 bg-maroonbgcolor flex justify-between items-center">
    <!-- Go Back button -->
    <a href="{{ route('addorder') }}" class="GoBackButton group">
        <img class="BackButton h-8 w-8 rounded-lg p-1" src="../img/gobackbutton.svg" alt="Go Back">
    </a>

    <!-- Center container for design tools -->
    <div class="designContainer flex items-center justify-center flex-grow">
        <!-- Pick color -->
        <div id="colorPickerContainer" class="rounded"></div>

        <!-- Toggle spin -->
        <button id="toggleSpin" class="ml-2 border rounded p-2 bg-red-500">
            <img src="img/spin-icon.svg" class="h-8 w-8">
        </button>

        <!-- Reset camera -->
        <button id="resetCamera" class="ml-2 border rounded p-2 bg-black text-white">
            <img src="img/reset-icon.svg" class="h-8 w-8">
        </button>

        <!-- Total Quantity Display -->
        <div class="relative ml-2">
            <button id="showSizeModal" class="border rounded p-2 bg-green-500 text-white text-center relative group">
                Amount
                <br>
                <span id="totalQuantity" class="font-bold">0</span>
                <!-- Quantity tooltip -->
                <span id="sizeQuantities" class="absolute left-0 bottom-full mb-2 hidden bg-black text-white text-sm rounded px-2 py-1 transition-opacity duration-300">
                    <div>XS = <span id="quantityXS">0</span></div>
                    <div>S = <span id="quantityS">0</span></div>
                    <div>M = <span id="quantityM">0</span></div>
                    <div>L = <span id="quantityL">0</span></div>
                    <div>XL = <span id="quantityXL">0</span></div>
                </span>
            </button>
        </div>

        <!-- Size modal -->
        <div id="sizeModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
            <div class="bg-maroonbgcolor rounded-lg p-6 shadow-lg w-96 text-white">
                <h2 class="text-xl font-bold mb-4 text-center">Modal</h2>

                <form id="sizeForm" method="POST" action="{{ route('qrcode') }}">
                    <table class="min-w-full border-collapse border border-gray-300 mx-auto">
                        <thead>
                            <tr>
                                <th class="border border-gray-300 p-2">Size</th>
                                <th class="border border-gray-300 p-2">Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border border-gray-300 p-2">Extra Small</td>
                                <td class="border border-gray-300 p-2">
                                    <input type="number" name="size_xs" min="0" value="0" class="border rounded-md w-full text-center text-black quantity" data-size="XS" />
                                </td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 p-2">Small</td>
                                <td class="border border-gray-300 p-2">
                                    <input type="number" name="size_s" min="0" value="0" class="border rounded-md w-full text-center text-black quantity" data-size="S" />
                                </td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 p-2">Medium</td>
                                <td class="border border-gray-300 p-2">
                                    <input type="number" name="size_m" min="0" value="0" class="border rounded-md w-full text-center text-black quantity" data-size="M" />
                                </td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 p-2">Large</td>
                                <td class="border border-gray-300 p-2">
                                    <input type="number" name="size_l" min="0" value="0" class="border rounded-md w-full text-center text-black quantity" data-size="L" />
                                </td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 p-2">Extra Large</td>
                                <td class="border border-gray-300 p-2">
                                    <input type="number" name="size_xl" min="0" value="0" class="border rounded-md w-full text-center text-black quantity" data-size="XL" />
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Back button -->
                    <div class="flex justify-center mt-4">
                        <button id="backButton" type="button" class="text-black rounded px-4 py-2 bg-white hover:bg-maroonbgcolor transition-transform duration-200 ease-in-out">Back</button>
                    </div>

                    <!-- Total Quantity Display -->
                    <div class="mt-4">
                        <h3 class="text-lg font-semibold">Total Quantity: <span id="modalTotalQuantity" class="text-green-500">0</span></h3>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Right-aligned confirm order button -->
    <button id="confirmOrder" class="ml-auto text-white p-2 rounded border border-white hover:bg-white hover:text-maroonbgcolor transition duration-300 ease-in-out">Confirm order</button>
</footer>


    @vite('resources/js/tshirt-customization.js') <!-- Load JavaScript specific to this page -->

<script>
document.getElementById('backButton').addEventListener('click', function() {
    document.getElementById('sizeModal').classList.add('hidden'); // Hide the modal
});

// Store the total quantity element
const totalQuantityElement = document.getElementById('totalQuantity');

// Show the modal and update quantities on button click
showSizeModal.addEventListener('click', () => {
    updateTotalQuantities(); // Update quantities before showing modal
    document.getElementById('sizeModal').classList.remove('hidden'); // Show the modal
});

// Function to update total quantities
const updateTotalQuantities = () => {
    const quantities = {
        XS: parseInt(document.querySelector('input[data-size="XS"]').value) || 0,
        S: parseInt(document.querySelector('input[data-size="S"]').value) || 0,
        M: parseInt(document.querySelector('input[data-size="M"]').value) || 0,
        L: parseInt(document.querySelector('input[data-size="L"]').value) || 0,
        XL: parseInt(document.querySelector('input[data-size="XL"]').value) || 0,
    };

    // Calculate total quantity
    const totalQuantity = Object.values(quantities).reduce((a, b) => a + b, 0);

    // Update display
    totalQuantityElement.innerText = totalQuantity; // Update main total quantity
    document.getElementById('modalTotalQuantity').innerText = totalQuantity; // Update modal display
};

// Update quantities in the tooltip on hover
showSizeModal.addEventListener('mouseenter', () => {
    const quantities = {
        XS: parseInt(document.querySelector('input[data-size="XS"]').value) || 0,
        S: parseInt(document.querySelector('input[data-size="S"]').value) || 0,
        M: parseInt(document.querySelector('input[data-size="M"]').value) || 0,
        L: parseInt(document.querySelector('input[data-size="L"]').value) || 0,
        XL: parseInt(document.querySelector('input[data-size="XL"]').value) || 0,
    };

    // Update the tooltip with the current quantities
    document.getElementById('quantityXS').innerText = quantities.XS;
    document.getElementById('quantityS').innerText = quantities.S;
    document.getElementById('quantityM').innerText = quantities.M;
    document.getElementById('quantityL').innerText = quantities.L;
    document.getElementById('quantityXL').innerText = quantities.XL;

    // Show the tooltip
    const sizeQuantities = document.getElementById('sizeQuantities');
    sizeQuantities.classList.remove('hidden');
});

// Restore the tooltip when mouse leaves
showSizeModal.addEventListener('mouseleave', () => {
    // Hide the tooltip
    const sizeQuantities = document.getElementById('sizeQuantities');
    sizeQuantities.classList.add('hidden');
});

// Attach event listeners to input fields for updating totals
document.querySelectorAll('.quantity').forEach(input => {
    input.addEventListener('input', updateTotalQuantities);
});

// Focus and blur event listeners for input fields
document.querySelectorAll('.quantity').forEach(input => {
    input.addEventListener('focus', function() {
        if (this.value === "0") {
            this.value = '';
        }
    });

    input.addEventListener('blur', function() {
        if (this.value === '') {
            this.value = '0'; // Reset to default value if empty
        }
    });
});

</script>

</body>
</html>

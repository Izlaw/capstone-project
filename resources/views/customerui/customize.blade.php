<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customize T-shirt</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @include('layouts.header')
    <style>
        /* Modal styling */
        #textModal {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        #textModal.hidden {
            display: none;
        }

        #textModal .modal-content {
            background: #fff;
            padding: 1.5rem;
            border-radius: 0.5rem;
            position: relative;
            width: 400px;
            max-width: 90%;
        }

        #textModal .modal-content h2 {
            margin-bottom: 1rem;
        }

        #textModal .modal-content input,
        #textModal .modal-content label {
            display: block;
            width: 100%;
        }

        #textModal .modal-content input[type="text"] {
            margin-bottom: 1rem;
            padding: 0.5rem;
            border: 1px solid #ccc;
            border-radius: 0.25rem;
        }

        #textModal .modal-content input[type="range"] {
            margin-bottom: 1rem;
        }

        #textModal .modal-content input[type="color"] {
            margin-bottom: 1rem;
            height: 2rem;
            padding: 0;
            border: none;
        }

        #modalPreviewContainer {
            position: relative;
            border: 1px solid #ccc;
            width: 100%;
            height: 200px;
            margin-bottom: 1rem;
            overflow: hidden;
        }

        #modalPreviewText {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            cursor: move;
        }

        #textModal .modal-close {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            font-size: 1.5rem;
            cursor: pointer;
        }
    </style>
</head>

<body class="font-sans antialiased flex flex-col min-h-screen overflow-hidden">
    <div class="flex-grow flex">
        <!-- Page Content -->
        <main class="flex-grow flex flex-col items-center">
            <div id="tshirt-container" class="w-full h-96">
                <!-- T-shirt model will render here -->
            </div>
        </main>
    </div>
    <footer class="Footer p-2 bg-primary flex justify-between items-center">
        <!-- Go Back button -->
        <x-backbutton route="addorder" />
        <!-- Center container for design tools -->
        <div class="designContainer flex items-center justify-center flex-grow">

            <!-- Reset functions -->
            <div class="mx-2 flex flex-col items-center bg-deep rounded-lg p-2">
                <span class="text-white font-semibold text-sm tracking-wider mb-2">Reset Camera</span>
                <button id="resetCamera" class="p-4 rounded-full bg-accent text-white transition-all duration-300 ease-in-out hover:bg-highlight hover:scale-110 focus:outline-none shadow-lg hover:shadow-xl">
                    <span class="relative inline-block">
                        <img src="img/reset-icon.svg" class="h-10 w-10 transition-all duration-300 ease-in-out transform hover:rotate-[-360deg] hover:animate-resetIcon" alt="reset camera">
                    </span>
                </button>

                <!-- Reset design button -->
                <div class="flex justify-center my-4">
                    <button id="resetDesign" class="px-4 py-2 bg-accent text-white rounded hover:bg-highlight transition-all duration-300 ease-in-out">
                        Reset Design
                    </button>
                </div>
            </div>

            <!-- Spin model -->
            <!-- <div class="mx-2 flex flex-col items-center bg-deep rounded-lg p-2">
                <span class="text-white font-semibold text-sm tracking-wider mb-2">Toggle Spin</span>
                <button id="toggleSpin" class="p-4 rounded-full bg-red-500 text-white transition-all duration-300 ease-in-out hover:bg-red-600 hover:scale-110 focus:outline-none shadow-lg hover:shadow-xl">
                    <span class="relative inline-block">
                        <img src="img/spin-icon.svg" class="h-10 w-10 transition-all duration-300 ease-in-out transform" alt="toggle spin">
                    </span>
                </button>
            </div> -->


            <!-- Pick color -->
            <div id="colorPickerContainer" class="mx-2 rounded-lg bg-deep p-4 shadow-lg hover:shadow-2xl hover:bg-highlight transition-all duration-300 ease-in-out">
                <div id="colorPicker"></div>
            </div>

            <!-- Toggle Buttons -->
            <div class="mx-2 grid grid-cols-2 gap-2 bg-deep rounded-lg p-2">
                <span class="col-span-2 text-white font-semibold text-sm tracking-wider mb-2">Toggle Parts</span>
                <button id="toggleSleeves" class="p-2 rounded bg-accent text-white">Sleeves</button>
                <button id="toggleFront" class="p-2 rounded bg-accent text-white">Front</button>
                <button id="toggleBack" class="p-2 rounded bg-accent text-white">Back</button>
                <button id="toggleCollar" class="p-2 rounded bg-accent text-white">Collar</button>
            </div>

            <!-- Size Selection -->
            <div class="flex items-center justify-center bg-deep mx-2 text-white p-4 rounded-lg shadow-lg transition-transform transform hover:scale-105 duration-300">
                <form id="sizeForm" method="POST" action="{{ route('qrcode') }}">
                    @csrf
                    <input type="hidden" name="model" value="tshirt">
                    <input type="hidden" name="text" id="textDataInput" value="">

                    <p class="text-center mb-2">Select Sizes</p>
                    <div id="sizeInputsContainer">
                        <button type="button" id="openSizeModalButton" class="bg-accent text-white py-2 px-4 rounded hover:bg-highlight transition">Select Sizes</button>
                    </div>
                </form>
                <div id="hoverWindow" class="fixed bg-secondary text-white px-4 py-2 rounded-lg shadow-lg hidden -mt-28">
                    <h3 class="text-base font-semibold mt-2">Please select a size</h3>
                    <ul id="selectedSizesList"></ul>
                </div>
            </div>
            <!-- Fabric Container -->
            <div class="FabricContainer mr-2 bg-deep p-2 rounded-lg">
                <!-- Fabric Type Input Field -->
                <label for="fabric_type" class="block text-sm font-medium text-white">Fabric Type</label>
                <select id="fabric_type" name="fabric_type" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-primary text-white">
                    <option value="" disabled selected class="text-gray-500">Select fabric type</option>
                    <option value="cotton" {{ old('fabric_type', $customOrder->fabric_type ?? '') == 'cotton' ? 'selected' : '' }}>Cotton</option>
                    <option value="polyester" {{ old('fabric_type', $customOrder->fabric_type ?? '') == 'polyester' ? 'selected' : '' }}>Polyester</option>
                    <option value="wool" {{ old('fabric_type', $customOrder->fabric_type ?? '') == 'wool' ? 'selected' : '' }}>Wool</option>
                    <option value="custom" {{ old('fabric_type', $customOrder->fabric_type ?? '') == 'custom' ? 'selected' : '' }}>Custom</option>
                </select>
                <input type="text" id="custom_fabric_type" name="custom_fabric_type" placeholder="Enter custom fabric type" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-primary text-white {{ old('fabric_type', $customOrder->fabric_type ?? '') == 'custom' ? '' : 'hidden' }}" value="{{ old('custom_fabric_type', $customOrder->custom_fabric_type ?? '') }}">
            </div>
            <!-- Text Customization Button -->
            <div class="mx-2">
                <button id="openTextModalButton" class="bg-accent text-white py-2 px-4 rounded hover:bg-highlight transition">Customize Text</button>
            </div>
        </div>
        <div class="flex flex-col items-center bg-deep text-center p-4 rounded-lg mr-2 text-white border-2 border-transparent hover:border-highlight transition-all duration-300 ease-in-out">
            <div>
                <p class="font-medium text-highlight">Total Amount</p>
                <p id="totalAmount" class="text-2xl font-semibold text-white">₱0.00</p>
            </div>
            <div>
                <button id="showPricingButton" class="bg-deep text-white p-3 rounded-lg hover:scale-125 transition">
                    <i class="bi bi-tags-fill text-xl"></i>
                </button>
            </div>
        </div>
        <!-- Confirm order -->
        <button id="confirmOrder" class="ml-auto text-white p-3 rounded-lg border-2 border-transparent bg-deep hover:bg-white hover:text-deep hover:border-deep transition-all duration-300 ease-in-out transform hover:scale-110 shadow-md hover:shadow-lg">
            Confirm Order
        </button>
    </footer>
    <!-- Text Customization Modal -->
    <div id="textModal" class="hidden">
        <div class="modal-content">
            <span id="modalCloseButton" class="modal-close">&times;</span>
            <h2>Customize Text</h2>
            <label for="modalInputText">Enter your text:</label>
            <input type="text" id="modalInputText" placeholder="Your text here">
            <label for="fontSizeSlider">Font Size:</label>
            <input type="range" id="fontSizeSlider" min="10" max="100" value="64">
            <label for="colorPickerInput">Text Color:</label>
            <input type="color" id="colorPickerInput" value="#ff0000">
            <div id="modalPreviewContainer">
                <div id="modalPreviewText">Your Text Here</div>
            </div>
            <div class="flex justify-end">
                <button id="modalCancelButton" class="bg-gray-500 text-white px-4 py-2 mr-2 rounded">Cancel</button>
                <button id="modalApplyButton" class="bg-accent text-white px-4 py-2 rounded">Apply Text</button>
            </div>
        </div>
    </div>
    @vite('resources/js/customize.js')
</body>

</html>
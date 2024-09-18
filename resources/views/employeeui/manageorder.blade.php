<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webpage Title</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @include('layouts.empheader')
</head>
<body class="bg-mainbackground bg-cover overflow-y-hidden">
    <div class=" flex flex-col items-center p-4 bg-brownbgcolor overflow-y-scroll h-65percent">   
        <!-- Header -->
        <div class="w-full max-w-7xl bg-gray-800 p-4 flex justify-between items-center rounded-t-lg">
            <div class="flex items-center space-x-2 text-gray-400">
                <span class="hover:text-white cursor-pointer">&larr; Home</span>
                <span>&#10148; Manage Orders</span>
            </div>
            <div class="text-2xl font-semibold text-white">Orders</div>
            <div>
                <img src="/path-to-profile.png" alt="Profile Icon" class="w-8 h-8 rounded-full cursor-pointer">
            </div>
        </div>

        <!-- Orders Table -->
        <div class="w-full max-w-7xl overflow-x-auto">
            <table class="table-auto w-full text-left bg-gray-800 rounded-b-lg shadow-lg">
                <thead>
                    <tr class="bg-brown-600">
                        <th class="px-4 py-2 text-sm text-white">Employee Name</th>
                        <th class="px-4 py-2 text-sm text-white">Customer Name</th>
                        <th class="px-4 py-2 text-sm text-white">Item Name</th>
                        <th class="px-4 py-2 text-sm text-white">Quantity</th>  
                        <th class="px-4 py-2 text-sm text-white">Size</th>
                        <th class="px-4 py-2 text-sm text-white">Total Price</th>
                        <th class="px-4 py-2 text-sm text-white">Schedule</th>
                        <th class="px-4 py-2 text-sm text-white">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Sample row -->
                    <tr class="border border-black">
                        <td class="px-4 py-2">Estanislao Delariarte III</td>
                        <td class="px-4 py-2">John Doe</td>
                        <td class="px-4 py-2">Male Uniform with Tie</td>
                        <td class="px-4 py-2">500</td>
                        <td class="px-4 py-2">167 (S), 167 (M), 166 (L)</td>
                        <td class="px-4 py-2">PHP 25,000.00</td>
                        <td class="px-4 py-2">
                            <p>9:00 AM <br> June 20, 2024</p>
                        </td>
                    </tr>

                    <!-- More example rows (use a loop in Laravel to generate these dynamically) -->
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
    
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Feedbacks</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @include('layouts.header')
</head>
<body class="bg-mainbackground bg-cover " style="gap:70px; height: calc(100vh - 48px)">

    <div class=" flex flex-col items-center p-4 bg-brownbgcolor bg-opacity-80 overflow-y-scroll h-65percent">  
        <h class= " text-3xl text-white font-bold "> FEEDBACKS </h> 
        

        <!-- Orders Table -->
        <div class="w-full max-w-7xl overflow-x-auto">
            <table class="table-auto w-full text-left rounded-b-lg shadow-lg">
                <thead>
                    <tr class="bg-brown-600">
                        <th class="px-4 py-2 text-sm text-white">Customer Name</th> <!-- users table  -->
                        <th class="px-4 py-2 text-sm text-white">Ratings</th>  
                        <th class="px-4 py-2 text-sm text-white">Comments</th>
                        <th class="px-4 py-2 text-sm text-white">Order</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Sample row (ih change ni dpat) -->
                    <tr class="border border-black">
                        <td class="px-4 py-2">Estanislao Delariarte III</td>
                        <td class="px-4 py-2">5 stars</td>
                        <td class="px-4 py-2">hahahhaha</td>
                        <td class="px-4 py-2">customized</td>
                        <td class="px-4 py-2 text-right"> <!-- Align text to the right -->
                        <button onclick="displayOrder(this)" class=" bg-white text-black py-2 px-4 rounded">DISPLAY</button>
                        <button onclick="deleteOrder(this)" class="text-white bg-red-500 py-2 px-4 rounded">DELETE</button>
                    </td>
                </tr>

                    
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
    
</body>
</html>
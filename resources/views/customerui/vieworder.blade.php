<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../output.css" rel="stylesheet">
    <title> View Order </title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @include('layouts.header')
</head>

<body class="bg-mainbackground bg-cover">  

        <div class="vieworderpgeContainer mx-auto bg-brownbgcolor w-3/4 bg-opacity-80  " style="height: calc(100vh - 48px);">

        <div class="GoBackColumn mb-8">
            <div class="GoBackContainer flex items-center">
                <a href="{{ route('home')}}" class="flex items-center">
                    <img class="GoBackButtonSvg mx-auto h-10 w-10 ml-4 " src="../img/gobackbutton.svg">
                </a>
            </div>
        </div>   

        <div class= " viewtable flex justify-center" >
            <table class=" rounded-lg text-center text-black border-spacing-x-16 border-separate ">
    <thead>
        <tr>
            <th class=" border-4 border-white py-2 w-32"> ORDER ID </th> 
            <th class="border-4 border-white py-2 w-32">TOTAL PRICE</th>
            <th class="border-4 border-white py-2 w-32">ORDER STATUS</th>
            <th class="border-4 border-white py-2 w-32">QUANTITY</th>
            <th class="border-4 border-white py-2 w-32">DATE ORDERED</th>
            <th class="border-4 border-white py-2 w-32">DATE RECEIVED</th>
            <th class="border-4 border-white py-2 w-32">PRODUCT</th>
        </tr>
    </thead>
        
    <tbody>
        @foreach($ViewOrder as $orders)
        <tr class="group hover:bg-white hover:text-black cursor-pointer hover:relative hover:z-10 " onclick="window.location.href='{{ route('orderDetails', $orders->orderID) }}'">
            <td class="py-2 w-32"> {{ $orders->orderID }} </a></td>
            <td class="py-2 w-32"> {{ $orders->totalPrice }} </td>
            <td class="py-2 w-32"> {{ $orders->orderStatus }} </td>
            <td class="py-2 w-32"> {{ $orders->orderTotal }} </td>
            <td class="py-2 w-32"> {{ $orders->dateOrder }} </td>
            <td class="py-2 w-32"> {{ $orders->dateReceived }} </td>
            <td class="py-2 w-32"> {{ $orders->productID }} </td>
        </tr>
        @endforeach
    </tbody>
    </table>
            </div>
            
        </div>

</body>
</html>
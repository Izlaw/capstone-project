<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../output.css" rel="stylesheet">
    <title> Order Details </title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @include('layouts.customerheader')
</head>

<body class="bg-mainbackground bg-cover">

    <div class=" orderDetailsContainer bg-brownbgcolor bg-opacity-80 " style=" gap: 70px; height: calc(100vh - 48px);">

        <div class="GoBackColumn mb-8">
            <div class="GoBackContainer flex items-center">
                <a href="{{ route('vieworder')}}" class="flex items-center">
                    <img class="GoBackButtonSvg mx-auto h-10 w-10 ml-4 " src="../img/gobackbutton.svg">
                </a>
            </div>
        </div> 

        <div class="text-4xl mt-8 text-center text-white ">
            <h1> Order Details</h1>
        </div>

        <!-- ang table nga ih connect sa orderDetails is ang product table lang -->
        <div class=" mx-auto p-4 flex justify-center scale-95"> 
            <!-- product details -->
            <div class="ProductDetails w-1/2 bg-white p-2 relative group mr-4"> 
                <p> Product Name: </p>
                <p> Materials Used </p>
                <p> Material Type: </p>
                <p> Material Color: </p>
                <p class=" space-y-5"> QR Code: </p>
                <p class=" space-y-40"> Billing Statement </p>
            </div>

             <!-- file kag ma view ang product in 3D -->
            <div class="ProductView  w-1/2 bg-white p-2 relative group">
                <div>
                    <p> Product: </p>
                </div>
                <div class=" flex flex-col items-center">
                    <img class="ProductViewjpeg h-1/2 w-1/2 " src="../img/schoolUni.jpeg">
                </div>
            </div>

        </div>

    </div>

</body>

</html>
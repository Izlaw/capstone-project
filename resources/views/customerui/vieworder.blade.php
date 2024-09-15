<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../output.css" rel="stylesheet">
    <title> View Order </title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @include('layouts.customerheader')
</head>
<body class="bg-mainbackground bg-cover">

        <div class="vieworderpgeContainer mx-auto bg-brownbgcolor w-3/4 bg-opacity-80  " style="height: calc(100vh - 48px);">
            <div class= " viewtable flex justify-center" >
            <table class="bg-white rounded-lg text-center space-y-5" style="border-collapse: separate; border-spacing: 22px;" >
    <thead>
        <tr>
            <th class=" border-4 border-black py-2 w-32" >ORDER ID </th>
            <th class="border-4 border-black py-2 w-32">TOTAL PRICE</th>
            <th class="border-4 border-black py-2 w-32">ORDER STATUS</th>
            <th class="border-4 border-black py-2 w-32">QUANTITY</th>
            <th class="border-4 border-black py-2 w-32">DATE ORDERED</th>
            <th class="border-4 border-black py-2 w-32">DATE RECEIVED</th>
            <th class="border-4 border-black py-2 w-32">CUSTOMER NAME</th>
            <th class="border-4 border-black py-2 w-32">PRODUCT</th>
        </tr>
    </thead>
    <tbody>
        <tr> <!-- example palang ni -->
            <td class=" border border-black ">001</td>
            <td class=" border border-black ">1000</td>
            <td class=" border border-black ">Pending</td>
            <td class=" border border-black ">50 pcs</td>
            <td class=" border border-black ">2024-09-10</td>
            <td class=" border border-black ">2024-08-10</td>
            <td class=" border border-black ">Bev Tamallana</td>
            <td class=" border border-black ">1001</td>
        </tr>
        <tr>
            <td class="border border-black ">002</td>
            <td class="border border-black">5000</td>
            <td class="border border-black">complete</td>
            <td class="border border-black">10 pcs</td>
            <td class="border border-black">2024-09-10</td>
            <td class="border border-black">2024-08-10</td>
            <td class="border border-black">Bev Tamallana</td>
            <td class="border border-black">1002</td>
        </tr>
        <tr>
            <td class="border border-black">003</td>
            <td class="border border-black">10000</td>
            <td class="border border-black">Ready for Pickup</td>
            <td class="border border-black">500 pcs</td>
            <td class="border border-black">2024-09-10</td>
            <td class="border border-black">2024-08-10</td>
            <td class="border border-black">Bev Tamallana</td>
            <td class="border border-black">1003</td>
        </tr>

        <?php // hindi pani fix kay need pa ih link ang echo sa database //
            echo "<td>";
            echo "<a onclick=\"return confirm('Are you sure you want to update this record?');\">Edit</a> | <a onclick=\"return confirm('Are you sure you want to delete this record?');\">Delete</a>";
            echo "</td>";

        ?>
    </tbody>
</table>
            </div>
            
        </div>

</body>
</html>
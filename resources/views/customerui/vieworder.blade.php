<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> View Order </title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @include('layouts.customerheader')
</head>
<body class="bg-mainbackground bg-cover overflow-y-hidden">

    <div class="ViewOrderContainer mx-auto bg-brownbgcolor w-screen bg-opacity-80 backdrop-blur-md h-screen">
        <div class= "ViewOrderTable w-full text-center">
            <table class="mx-auto bg-white rounded">
            <thead>
                <tr>
                    <th class="border border-black p-3">ORDER ID</th>
                    <th class="border border-black p-3">TOTAL PRICE</th>
                    <th class="border border-black p-3">ORDER STATUS</th>
                    <th class="border border-black p-3">QUANTITY</th>
                    <th class="border border-black p-3">DATE ORDERED</th>
                    <th class="border border-black p-3">DATE RECEIVED</th>
                    <th class="border border-black p-3">CUSTOMER NAME</th>
                    <th class="border border-black p-3">PRODUCT</th>
                    </tr>
            </thead>
        <tbody>
            <tr>
                <td class="border border-black p-3">001</td>
                <td class="border border-black p-3">1000</td>
                <td class="border border-black p-3">Pending</td>
                <td class="border border-black p-3">50 pcs</td>
                <td class="border border-black p-3">2024-09-10</td>
                <td class="border border-black p-3">2024-08-10</td>
                <td class="border border-black p-3">Bev Tamallana</td>
                <td class="border border-black p-3">1001</td>
            </tr>
            <tr>
                <td class="border border-black p-3">002</td>
                <td class="border border-black p-3">5000</td>
                <td class="border border-black p-3">Complete</td>
                <td class="border border-black p-3">10 pcs</td>
                <td class="border border-black p-3">2024-09-10</td>
                <td class="border border-black p-3">2024-08-10</td>
                <td class="border border-black p-3">Bev Tamallana</td>
                <td class="border border-black p-3">1002</td>
            </tr>
            <tr>
                <td class="border border-black p-3">003</td>
                <td class="border border-black p-3">10000</td>
                <td class="border border-black p-3">Ready for Pickup</td>
                <td class="border border-black p-3">500 pcs</td>
                <td class="border border-black p-3">2024-09-10</td>
                <td class="border border-black p-3">2024-08-10</td>
                <td class="border border-black p-3">Bev Tamallana</td>
                <td class="border border-black p-3">1003</td>
            </tr>
        </tbody>


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
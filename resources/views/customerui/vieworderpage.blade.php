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
            <div class= " viewtable w-full absolute " style="top: 50%; transform: translateY(-50%); text-align: center; display: flex; justify-content: center; " >
            <table class="mx-auto" style="background-color: white; border: 5px solid #660000; border-collapse: collapse;">
    <thead>
        <tr>
            <th style="border: 1px solid #ccc; padding: 12px;">ORDER ID </th>
            <th style="border: 1px solid #ccc; padding: 12px;">TOTAL PRICE</th>
            <th style="border: 1px solid #ccc; padding: 12px;">ORDER STATUS</th>
            <th style="border: 1px solid #ccc; padding: 12px;">QUANTITY</th>
            <th style="border: 1px solid #ccc; padding: 12px;">DATE ORDERED</th>
            <th style="border: 1px solid #ccc; padding: 12px;">DATE RECEIVED</th>
            <th style="border: 1px solid #ccc; padding: 12px;">CUSTOMER NAME</th>
            <th style="border: 1px solid #ccc; padding: 12px;">PRODUCT</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="border: 1px solid #ccc; padding: 10px;">001</td>
            <td style="border: 1px solid #ccc; padding: 10px;">1000</td>
            <td style="border: 1px solid #ccc; padding: 10px;">Pending</td>
            <td style="border: 1px solid #ccc; padding: 10px;">50 pcs</td>
            <td style="border: 1px solid #ccc; padding: 10px;">2024-09-10</td>
            <td style="border: 1px solid #ccc; padding: 10px;">2024-08-10</td>
            <td style="border: 1px solid #ccc; padding: 10px;">Bev Tamallana</td>
            <td style="border: 1px solid #ccc; padding: 10px;">1001</td>
        </tr>
        <tr>
            <td style="border: 1px solid #ccc; padding: 10px;">002</td>
            <td style="border: 1px solid #ccc; padding: 10px;">5000</td>
            <td style="border: 1px solid #ccc; padding: 10px;">complete</td>
            <td style="border: 1px solid #ccc; padding: 10px;">10 pcs</td>
            <td style="border: 1px solid #ccc; padding: 10px;">2024-09-10</td>
            <td style="border: 1px solid #ccc; padding: 10px;">2024-08-10</td>
            <td style="border: 1px solid #ccc; padding: 10px;">Bev Tamallana</td>
            <td style="border: 1px solid #ccc; padding: 10px;">1002</td>
        </tr>
        <tr>
            <td style="border: 1px solid #ccc; padding: 10px;">003</td>
            <td style="border: 1px solid #ccc; padding: 10px;">10000</td>
            <td style="border: 1px solid #ccc; padding: 10px;">Ready for Pickup</td>
            <td style="border: 1px solid #ccc; padding: 10px;">500 pcs</td>
            <td style="border: 1px solid #ccc; padding: 10px;">2024-09-10</td>
            <td style="border: 1px solid #ccc; padding: 10px;">2024-08-10</td>
            <td style="border: 1px solid #ccc; padding: 10px;">Bev Tamallana</td>
            <td style="border: 1px solid #ccc; padding: 10px;">1003</td>
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
<?php

namespace App\Http\Controllers;

use App\Models\CustomOrder; // Import your model
use Illuminate\Http\Request;

class CustomOrderController extends Controller
{
    public function show($id)
    {
        // Retrieve the custom order from the database using the ID
        $order = CustomOrder::findOrFail($id);

        // Return the view and pass the order data
        return view('previeworder', ['order' => $order]);
    }
}

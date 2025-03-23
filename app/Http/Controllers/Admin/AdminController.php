<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Size;

class AdminController extends Controller
{

    public function updateSizes(Request $request)
    {
        // Loop through each price and update the corresponding size
        foreach ($request->prices as $sizeID => $price) {
            $size = Size::find($sizeID); // Find the size by its ID
            if ($size) {
                $size->sizePrice = $price; // Update the price
                $size->save(); // Save the changes
            }
        }

        // Redirect back with a success message
        return redirect()->route('manageprices')->with('success', 'Prices updated successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;  // To access the DB directly

class SizeController extends Controller
{
    public function fetchSizes()
    {
        // Retrieve sizes using Eloquent ORM
        $sizes = Size::select('sizeName', 'sizePrice', 'sizeID')->get();

        // Return the data to the view
        return view('adminui.adminprices', compact('sizes'));
    }
}

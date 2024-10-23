<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;

class CustomizeTShirtController extends Controller
{
    /**
     * Show the t-shirt customization page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('customizetshirt'); // Return the Blade view for customization
    }
}

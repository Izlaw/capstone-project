<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ViewOrderController extends Controller
{
    public function index()
    {
        $ViewOrder = \App\Models\orders::all();
        return view('customerui.ViewOrder', compact('ViewOrder'));
    }
}

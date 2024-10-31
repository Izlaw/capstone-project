<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AddOrderController extends Controller
{
    public function index()
    {
        return view('customerui.addorder');
    }

    public function askGender()
    {
        $gender = $request->input('gender'); 
        return view('addcustomorder', compact('gender')); 
    }

    public function addCustomOrder($gender)
    {
        return view('customerui.addcustomorder', ['gender' => $gender]);
    }

    public function uploadCustomOrder()
    {
        return view('customerui.uploadorder');
    }
}

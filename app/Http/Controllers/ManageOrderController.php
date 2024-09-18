<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManageOrderController extends Controller
{
    public function index()
    {
        return view('employeeui.manageorder'); 
    }
}

<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ManageOrderController extends Controller
{
    public function index()
    {
        return view('employeeui.manageorder'); 
    }
}

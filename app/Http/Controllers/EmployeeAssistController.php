<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EmployeeAssistController extends Controller
{
    public function index()
    {
        return view('employeeui.empassist');
    }
}

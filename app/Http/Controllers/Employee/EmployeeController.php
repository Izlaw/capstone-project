<?php

namespace App\Http\Controllers\Employee;

use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        return view('employeeui.empdboard');
    }
}

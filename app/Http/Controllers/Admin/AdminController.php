<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('adminui.admindboard'); // Employee dashboard view
    }
}

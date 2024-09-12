<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        if (auth()->user()->role == 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif (auth()->user()->role == 'employee') {
            return redirect()->route('employee.dashboard');
        } else {
            return redirect()->route('home');
        }
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class Login1Controller extends Controller
{
    public function index()
    {
        return view('login1');
    }

}
<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class adminFeedController extends Controller
{
    public function index()
    {
        return view('adminui.adminFeed');
    }
}

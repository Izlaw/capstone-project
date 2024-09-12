<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UploadOrderController extends Controller
{
    public function index()
    {
        return view('customerui.uploadorder');
    }
}
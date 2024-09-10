<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChatSupportController extends Controller
{
    public function index()
    {
        return view('chatsupport');
    }
}

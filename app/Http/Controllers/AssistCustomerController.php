<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class AssistCustomerController extends Controller
{
    public function index()
    {
        // Fetch conversations for the authenticated employee
        // Adjust the query according to your schema and relationships
        $userId = Auth::id(); // or however you get the current user's ID
        $conversations = Message::select('user_id')
            ->where('status', 'active') // Adjust as needed
            ->groupBy('user_id')
            ->with('user') // Assuming you have a relationship named 'user'
            ->get();

        return view('employeeui.assistcustomer', compact('conversations'));
    }
}

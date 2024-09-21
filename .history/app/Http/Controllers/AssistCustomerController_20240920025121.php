<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AssistCustomerController extends Controller
{
    public function index()
    {
        // This method lists all conversations
        $userId = Auth::id();
        $conversations = Message::select('user_id')
            ->where('status', 'active')
            ->where('user_id', '!=', $userId)
            ->groupBy('user_id')
            ->with('user')
            ->get();
        
        return view('employeeui.assistcustomer', compact('conversations'));
    }

    public function showConversations()
    {
        $userId = Auth::id(); // Get the current employee's ID

        // Fetch the most recent message for each user
        $conversations = Message::select('user_id', 'content', 'date')
            ->where('status', 'active')
            ->where('user_id', '!=', $userId)
            ->latest('date') // Order by date to get the latest message
            ->get()
            ->groupBy('user_id') // Group by user ID
            ->map(function ($messages) {
                return $messages->first(); // Get the most recent message for each user
            });

        // Load user details for each conversation
        $conversations = $conversations->map(function ($message) {
            $message->user = $message->user; // Load the user relationship
            return $message;
        });

        return view('employeeui.assistcustomer', compact('conversations'));
    }
}


<?php

namespace App\Http\Controllers\Employee;

use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\Controller;


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

    public function showChat($recipient)
    {
        $recipientUser = User::find($recipient);
        if (!$recipientUser) {
            abort(404, 'Recipient not found');
        }

        $userId = Auth::id(); // Get the current authenticated user's ID

        // Fetch messages between the authenticated user and the recipient
        $messages = Message::where(function ($query) use ($recipient, $userId) {
            $query->where('user_id', $userId)
                ->where('conversation_id', $recipient); // Adjust if conversation_id is the recipient ID
        })->orWhere(function ($query) use ($recipient, $userId) {
            $query->where('user_id', $recipient)
                ->where('conversation_id', $userId); // Adjust if conversation_id is the recipient ID
        })->with('user') // Load the user relationship
        ->get();

        return view('chat', ['recipient' => $recipientUser, 'messages' => $messages]);
    }
}


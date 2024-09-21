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
        try {
            $userId = Auth::id(); // Get the current authenticated user's ID
            $recipientUser = User::find($recipient);
            if (!$recipientUser) {
                abort(404, 'Recipient not found');
            }

            // Fetch messages between the authenticated user and the recipient
            $messages = Message::where(function ($query) use ($recipient, $userId) {
                $query->where('user_id', $userId)
                      ->where('recipient_id', $recipient); // Adjust as needed
            })->orWhere(function ($query) use ($recipient, $userId) {
                $query->where('user_id', $recipient)
                      ->where('recipient_id', $userId); // Adjust as needed
            })->with('user')
            ->get();

            return view('chat', [
                'recipient' => $recipientUser,
                'messages' => $messages
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to fetch messages: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch messages.'], 500);
        }
    }
}


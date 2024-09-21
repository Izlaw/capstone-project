<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class AssistCustomerController extends Controller
{
    public function index()
    {
        // Adjust the query according to your schema and relationships
        $userId = Auth::id(); // or however you get the current user's ID
        $conversations = Message::select('user_id')
            ->where('status', 'active') // Adjust as needed
            ->groupBy('user_id')
            ->with('user') // Assuming you have a relationship named 'user'
            ->get();

        return view('employeeui.assistcustomer', compact('conversations'));
    }
    
    public function getMessages($conversationId)
    {
        try {
            $userId = Auth::id(); // Get the current authenticated user's ID
    
            // Fetch messages between the authenticated user and the recipient
            $messages = Message::where(function ($query) use ($conversationId, $userId) {
                $query->where('user_id', $userId)
                    ->where('conversation_id', $conversationId); // Adjust as needed
            })->orWhere(function ($query) use ($conversationId, $userId) {
                $query->where('user_id', $conversationId)
                    ->where('conversation_id', $userId); // Adjust as needed
            })->with('user') // Load the user relationship
            ->get();
    
            return response()->json($messages);
        } catch (\Exception $e) {
            \Log::error('Failed to fetch messages: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch messages.'], 500);
        }
    }

}

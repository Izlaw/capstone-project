<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\MessageSent; // Ensure you import your event if used
use Illuminate\Support\Facades\Auth; // For user authentication
use App\Models\Message; // Import your Message model
use App\Events\ConversationUpdated; // Import your ConversationUpdated event
use App\Models\User; // Import your User model

class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:255',
            'conversation_id' => 'required|exists:conversations,conversation_id',
        ]);

        $messageContent = $request->input('message');
        $user = Auth::user(); // Get the currently authenticated user

        // Create a new message instance
        $message = new Message();
        $message->content = $messageContent;
        $message->date = now();
        $message->status = 'active';
        $message->user_id = $user->user_id;
        $message->save();
        
        // Broadcast the message event
        broadcast(new MessageSent($user, $message));

        // Return a JSON response
        return response()->json([
            'status' => 'Message sent successfully!',
            'message' => $messageContent,
            'user' => $user->name,
            'status' => $message->status,
            'date' => $message->date,
        ]);
    }

    public function getMessages($conversationId)
    {
        try {
            $userId = Auth::id(); // Current authenticated user
    
            // Fetch messages based on the user IDs involved
            $messages = Message::where(function ($query) use ($conversationId, $userId) {
                $query->where('user_id', $userId)
                      ->where('conversation_id', $conversationId); // Adjust if using a different identifier
            })->orWhere(function ($query) use ($conversationId, $userId) {
                $query->where('user_id', $conversationId)
                      ->where('conversation_id', $userId); // Adjust if using a different identifier
            })->with('user') // Ensure you have this relationship in the Message model
            ->get();
    
            return response()->json($messages);
        } catch (\Exception $e) {
            \Log::error('Failed to fetch messages: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch messages.'], 500);
        }
    }


    public function redirectToRandomEmployee()
    {
        // Fetch a random employee user
        $randomEmployee = User::where('role', 'employee')->inRandomOrder()->first();

        // Redirect to the chat page with the selected employee ID
        if ($randomEmployee) {
            return redirect()->route('chat', ['recipient' => $randomEmployee->user_id]);
        } else {
            abort(404, 'No employees found');
        }
    }
}


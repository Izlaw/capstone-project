<?php

namespace App\Http\Controllers\Employee;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Conversation;  // Import the Conversation model
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Events\ConversationCreated;
use App\Events\MessageSent;


class AssistCustomerController extends Controller
{
    public function listConversations()
    {
        // Fetch all conversations (or a subset as needed)
        $conversations = Conversation::all(); // Adjust the query as per your needs

        // Return the 'assistcustomer' view with the conversations
        return view('employeeui.assistcustomer', compact('conversations'));
    }

    public function showConversations()
    {
        // Get the current employee's ID
        $userId = Auth::id();
        Log::debug('Employee Controller: showConversations method called', ['employee_id' => $userId]);

        // Retrieve conversations where the user is not the current employee and is a customer
        $conversations = Conversation::where('user_id', '!=', $userId)
            ->whereHas('user', function ($query) {
                $query->where('role', 'customer');
            })
            ->with(['messages' => function ($query) {
                // Get the latest message for each conversation by 'messDate'
                $query->latest('messDate')->limit(1);
            }])
            ->with('user') // Eager load the user to check the user's info easily
            ->get();

        // Log the retrieved conversations
        Log::debug('Employee Controller: Conversations retrieved', [
            'conversation_count' => $conversations->count(),
            'conversations' => $conversations->toArray(),
        ]);

        // Iterate over the conversations to attach the latest message
        $conversations->map(function ($conversation) use ($userId) {
            // Get the latest message from the eager-loaded messages
            $message = $conversation->messages->first(); // Latest message (first item)

            // Log the latest message details or if no message is found
            if ($message) {
                Log::debug('Employee Controller: Latest message found for conversation', [
                    'convoID' => $conversation->convoID,
                    'messID' => $message->messID,
                    'content' => $message->messContent,
                ]);
            } else {
                Log::debug('Employee Controller: No messages found for conversation', ['convoID' => $conversation->convoID]);
            }

            // Attach the latest message to the conversation object
            $conversation->latestMessage = $message; // Add the latest message content to the conversation

            // Log the status of attaching the latest message to the conversation
            Log::debug('Employee Controller: Latest message attached', [
                'convoID' => $conversation->convoID,
                'latestMessage' => $message ? $message->messContent : 'No message',
            ]);

            return $conversation;
        });

        // Log the number of conversations retrieved
        Log::debug('Employee Controller: Total conversations fetched', ['conversation_count' => $conversations->count()]);

        // Return the 'assistcustomer' view with the list of conversations
        return view('employeeui.assistcustomer', [
            'conversations' => $conversations,
        ]);
    }

    public function showChat($convoID)
    {
        // Log the convoID with a label for the employee controller
        Log::debug('Employee Controller: showChat method called', ['convoID' => $convoID]);

        // Log the type of the $convoID to check if it's an integer or string
        Log::debug('convoID type: ', ['type' => gettype($convoID)]);

        // Attempting to find the conversation for the given convoID
        Log::debug('Attempting to find conversation for convoID: ' . $convoID);

        // Query the Conversation model for the given convoID
        $conversation = Conversation::where('convoID', $convoID)->first();

        // Log the SQL query (ensure you enable query logging in AppServiceProvider)

        if (!$conversation) {
            // Log if the conversation was not found
            Log::debug('Employee Controller: Conversation not found for convoID', ['convoID' => $convoID]);
            return redirect()->route('home')->with('error', 'Conversation not found.');
        }

        // Log the conversation details once found
        Log::debug('Employee Controller: Conversation found', ['conversation' => $conversation]);

        // Retrieve the messages for the conversation
        $messages = Message::where('convoID', $convoID)->get();

        // Log the number of messages retrieved
        Log::debug('Employee Controller: Messages fetched', ['message_count' => $messages->count()]);

        // Check if the current user is authorized to view the conversation
        if (!Auth::user()->isEmployee()) {
            // Log if the user is not authorized
            Log::debug('Employee Controller: Unauthorized access attempt', ['user_id' => Auth::id()]);
            return redirect()->route('home')->with('error', 'You are not authorized to view this conversation.');
        }

        // Log the user authorization check
        Log::debug('Employee Controller: User authorized', ['user_id' => Auth::id()]);

        // Retrieve the customer (the user who started the conversation)
        $recipient = User::find($conversation->user_id);

        if (!$recipient) {
            // Log if the customer is not found
            Log::debug('Employee Controller: Customer not found', ['user_id' => $conversation->user_id]);
            return redirect()->route('home')->with('error', 'Customer not found.');
        }

        // Log the customer details
        Log::debug('Employee Controller: Customer found', ['recipient' => $recipient]);

        // Pass the conversation, messages, recipient, and convoID to the view
        return view('chat', compact('conversation', 'messages', 'convoID', 'recipient'));
    }
}

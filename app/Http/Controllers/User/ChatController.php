<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\MessageSent; // Ensure you import your event if used
use Illuminate\Support\Facades\Auth; // For user authentication
use App\Models\Message; // Import your Message model
use App\Models\User; // Import your User model
use Illuminate\Support\Facades\Log;
use App\Models\Conversation;

class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        // Log the incoming request data for debugging
        Log::info('Received new message:', $request->all());

        $validatedData = $request->validate([
            'message' => 'required|string',
            'convoID' => 'required|integer',
        ]);

        // Log after validation to confirm validation passed
        Log::info('Validation passed with data:', $validatedData);

        $conversation = Conversation::find($validatedData['convoID']);
        if (!$conversation) {
            // Log if the conversation ID is not found
            Log::warning('Conversation not found for convoID:', ['convoID' => $validatedData['convoID']]);
            return response()->json(['error' => 'Invalid conversation ID'], 400);
        }

        // Log the conversation that was found
        Log::info('Found conversation:', ['conversation' => $conversation]);

        // Create a new message
        $message = new Message([
            'messContent' => $validatedData['message'],
            'messDate' => now(),
            'user_id' => Auth::id(),
            'convoID' => $conversation->convoID,
        ]);

        // Log the message object to check its properties
        Log::info('Message object before save:', ['message' => $message]);

        $message->save(); // Save the message

        // Log after saving the message
        Log::info('Message saved successfully:', ['message_id' => $message->messID]);

        // Update the messID in the conversation table to point to the new message
        $conversation->messID = $message->messID;
        $conversation->save(); // Save the updated conversation

        // Log after updating the conversation with new messID
        Log::info('Updated conversation with new messID:', ['convoID' => $conversation->convoID, 'messID' => $conversation->messID]);

        // Dispatch the event to broadcast the message
        broadcast(new MessageSent(Auth::user(), $message));

        // Log that the event was dispatched
        Log::info('MessageSent event broadcasted', ['message_id' => $message->messID]);

        return response()->json(['success' => true]);
    }

    public function getMessages($conversationId)
    {
        Log::info('Fetching messages for conversation', ['conversationId' => $conversationId]);

        try {
            $userId = Auth::id(); // Current authenticated user

            // Fetch messages where convoID matches the given conversation ID
            $messages = Message::where('convoID', $conversationId)
                ->with('user') // Ensure you have this relationship in the Message model
                ->get();

            // Log successful message retrieval
            Log::info('Messages retrieved successfully', [
                'user_id' => $userId,
                'conversation_id' => $conversationId,
                'message_count' => $messages->count(),
            ]);

            // Return messages with conversation ID for context
            return response()->json([
                'conversation_id' => $conversationId,
                'messages' => $messages,
            ]);
        } catch (\Exception $e) {
            // Log error on exception
            Log::error('Failed to fetch messages', [
                'error' => $e->getMessage(),
                'conversation_id' => $conversationId,
            ]);
            return response()->json(['error' => 'Failed to fetch messages.'], 500);
        }
    }

    public function showChat($convoID)
    {
        // dd($convoID); // Check what $convoID is before processing.

        Log::info('showChat method started', [
            'convoID' => $convoID,
            'user_id' => Auth::id(),
        ]);

        // Fetch the conversation by convoID
        $conversation = Conversation::where('convoID', $convoID)
            ->where('user_id', Auth::id())  // Ensure the conversation belongs to the current user
            ->first();

        if ($conversation) {
            Log::info('Conversation found', [
                'convoID' => $convoID,
                'user_id' => Auth::id(),
            ]);

            // Fetch messages for the conversation
            $messages = Message::where('convoID', $convoID)->get();

            // Check if the employee is already assigned (via session or cache)
            $recipient = session("conversation_{$convoID}_employee");

            if (!$recipient) {
                // If no employee is assigned, assign a random employee
                $recipient = User::where('role', 'employee')->inRandomOrder()->first();

                if ($recipient) {
                    // Store the recipient in the session to prevent reassignment
                    session(["conversation_{$convoID}_employee" => $recipient]);

                    Log::info('Random employee assigned', [
                        'convoID' => $convoID,
                        'recipient_id' => $recipient->user_id,
                        'recipient_name' => $recipient->name,
                    ]);
                } else {
                    Log::error('No employee found for conversation', [
                        'convoID' => $convoID,
                        'user_id' => Auth::id(),
                    ]);
                    return redirect()->route('home')->with('error', 'No employee available.');
                }
            } else {
                Log::info('Employee already assigned to conversation', [
                    'convoID' => $convoID,
                    'recipient_id' => $recipient->user_id,
                    'recipient_name' => $recipient->name,
                ]);
            }

            // dd($conversation, $messages, $recipient, $convoID);

            // Pass the conversation, messages, recipient, and convoID to the view
            return view('chat', [
                'conversation' => $conversation,
                'messages' => $messages,
                'recipient' => $recipient,  // Include the recipient (employee)
                'convoID' => $convoID,      // Pass the convoID for the meta tag
            ]);
        } else {
            Log::error('Conversation not found', [
                'convoID' => $convoID,
                'user_id' => Auth::id(),
            ]);
            abort(404, 'Conversation not found');
        }
    }
}

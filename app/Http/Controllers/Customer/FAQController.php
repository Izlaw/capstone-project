<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth; // For user authentication
use App\Models\User; // Import your User model
use App\Models\Message; // Import your Message model
use App\Models\Conversation;

class FAQController extends Controller
{

    public function askSupport()
    {
        Log::info('askSupport method started for user', [
            'user_id' => Auth::id(),
        ]);

        // Fetch a random employee user
        $randomEmployee = User::where('role', 'employee')->inRandomOrder()->first();

        if ($randomEmployee) {
            Log::info('Customer redirected to a random employee', [
                'customer_id' => Auth::id(),
                'random_employee_id' => $randomEmployee->user_id,
            ]);

            // Create a new conversation first
            $conversation = Conversation::create([
                'user_id' => Auth::id(),  // Customer user_id
                'messID' => null, // We'll link the first message after this
            ]);

            Log::info('New conversation created', [
                'convoID' => $conversation->convoID,
                'customer_id' => Auth::id(),
            ]);

            // Create the initial message and associate it with the conversation
            $message = Message::create([
                'user_id' => Auth::id(),  // Customer who sent the message
                'messContent' => 'Hi! I need assistance.', // Message content
                'convoID' => $conversation->convoID, // Use the convoID from the conversation
                'messDate' => now(), // Set current timestamp for the message
            ]);

            Log::info('Initial message created', [
                'message_id' => $message->messID,
                'message_content' => $message->messContent,
                'convoID' => $conversation->convoID, // Log the convoID with the message
            ]);

            // Redirect to the customer chat interface
            return redirect()->route('supportchat', ['convoID' => $conversation->convoID]);
        } else {
            Log::error('No employees found for support chat', [
                'customer_id' => Auth::id(),
            ]);
            abort(404, 'No employees found');
        }
    }
}

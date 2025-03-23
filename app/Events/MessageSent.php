<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Support\Facades\Log;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $message;
    public $senderId;  // Ensure this is included

    /**
     * Create a new event instance.
     *
     * @param $user
     * @param $message
     */
    public function __construct($user, $message)
    {
        // Log the user and message data to confirm it is being passed correctly
        Log::info('MessageSent Event Constructor: ', [
            'user' => $user,    // Log the full user object to check its structure
            'message' => $message
        ]);

        // Check if $user is an object
        if (is_object($user)) {
            Log::info('User object:', [
                'user_id' => $user->user_id,   // Log user_id if it's an object
                'first_name' => $user->first_name,  // Log first_name
                'last_name' => $user->last_name   // Log last_name
            ]);
        } else {
            Log::warning('User is not an object:', $user);  // Log if it's not an object
        }

        $this->user = $user;
        $this->senderId = $user->user_id;  // Make sure senderId is passed

        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // Log the channel name to confirm it's being set correctly
        $channelName = 'chat.' . $this->message->convoID;
        Log::info('Broadcasting on channel: ' . $channelName);

        return new PrivateChannel($channelName);
    }

    /**
     * Get the data that should be broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        // Log the data being sent with the broadcast
        $data = [
            'user' => [
                'first_name' => $this->user->first_name,
                'last_name' => $this->user->last_name
            ],
            'message' => $this->message->messContent,
            'date' => $this->message->messDate->toDateTimeString(),
            'senderId' => $this->senderId,                                // Broadcast the senderId
            'convoID' => $this->message->convoID                         // Conversation ID
        ];

        Log::info('Broadcasting data: ', $data);

        return $data;
    }
}

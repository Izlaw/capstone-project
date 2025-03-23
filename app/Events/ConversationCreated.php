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
use App\Models\Conversation;
use App\Models\Message;

class ConversationCreated implements ShouldBroadcastNow
{
    use InteractsWithSockets, SerializesModels;

    public $conversation;

    // Constructor
    public function __construct(Conversation $conversation)
    {
        $this->conversation = $conversation;
    }

    // Broadcasts the event on a specific channel
    public function broadcastOn()
    {
        Log::info("Broadcasting ConversationCreated event to channel: conversation.{$this->conversation->convoID}");
        return new Channel('conversations');
    }

    // Optionally, add a broadcast name
    public function broadcastAs()
    {
        return 'ConversationCreated';
    }
}

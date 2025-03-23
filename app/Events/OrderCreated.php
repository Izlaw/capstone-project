<?php

namespace App\Events;

use App\Models\Order;
use App\Models\CustomOrder;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class OrderCreated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $order;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function __construct($order)
    {
        // You can check if the passed $order is an instance of either Order or CustomOrder
        if ($order instanceof Order || $order instanceof CustomOrder) {
            $this->order = $order;
        } else {
            throw new \InvalidArgumentException('Expected Order or CustomOrder model');
        }
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        \Log::info('Broadcasting OrderCreated event on channel: created-orders');
        return new Channel('created-orders'); // Same channel as before
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'OrderCreated'; // Use 'OrderCreated' as the event name for broadcasting
    }
}

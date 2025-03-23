<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;
use App\Models\Collection;
use App\Models\Conversation;
use App\Models\CustomOrder;
use App\Models\Feedback;
use App\Models\Message;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Size;
use App\Models\UploadOrder;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('chat.{conversation_id}', function ($user, $conversation_id) {
    // Customize the authorization logic as needed
    return true; // Allow all users to listen for simplicity (use proper checks for security)
});


Broadcast::channel('private-chat{conversation_id}', function ($user, $conversation_id) {
    $isAuthorized = Conversation::where('convoID', $conversation_id)
        ->where('user_id', $user->id)
        ->exists();
    \Log::info("User {$user->id} authorization for convoID {$conversation_id}: {$isAuthorized}");

    return $isAuthorized;
});

Broadcast::channel('order.{orderID}', function ($user, $orderID) {
    // Only allow the user who created the order to listen to the channel
    $order = Order::findOrFail($orderID);
    return $user->id === $order->user_id; // Ensures the order owner can access the channel
});

Broadcast::channel('conversation.{convoID}', function ($user, $convoID) {
    return true; 
});

Broadcast::channel('order-status', function ($user) {
    return true; 
});




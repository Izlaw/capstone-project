<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Pusher\Pusher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PusherAuthController extends Controller
{
    public function authenticate(Request $request)
{
    // Log incoming request
    Log::info('Authentication request received for channel: ' . $request->input('channel_name'));

    // Ensure the user is logged in
    if (!Auth::check()) {
        Log::error('User not authenticated');
        return response('Unauthorized', 401);
    }

    // Get the channel name from the request
    $channelName = $request->input('channel_name');
    Log::info('Channel Name: ' . $channelName);

    // Check if the user is allowed to join the channel
    if (strpos($channelName, 'private-chat') === 0) {
        $user_id = Auth::id();
        $socket_id = $request->input('socket_id');

        // Log user details
        Log::info('User ID: ' . $user_id . ', Socket ID: ' . $socket_id);

        // Initialize the Pusher object
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'useTLS' => true
            ]
        );

        // Generate the authentication response for private channel
        $auth = $pusher->socket_auth($channelName, $socket_id);
        Log::info('Pusher authentication successful for channel: ' . $channelName);

        return response($auth);
    }

    Log::error('Unauthorized channel access');
    return response('Unauthorized', 401);
}


}

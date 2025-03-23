<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use App\Events\ExampleEvent;

class LogController extends Controller
{
    /**
     * Log a custom message.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function logMessage(Request $request)
    {
        // Get message and other info from the request
        $message = $request->input('message');
        $type = $request->input('type', 'info'); // default to 'info'

        // Log the message based on the type
        switch ($type) {
            case 'error':
                Log::error($message);
                break;
            case 'warning':
                Log::warning($message);
                break;
            default:
                Log::info($message);
        }

        return response()->json(['status' => 'Message logged successfully']);
    }
    public function triggerEvent()
    {
        broadcast(new ExampleEvent('Hello, world!'));
    }
}

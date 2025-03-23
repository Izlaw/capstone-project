<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use App\Models\Conversation;
use App\Models\UploadOrder;
use App\Models\Order;
use App\Models\Size;
use Illuminate\Support\Facades\Log;
use App\Events\ConversationCreated;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\BillingStatementController;

class UploadOrderController extends Controller
{
    public function uploadDesignAndSendMessage(Request $request)
    {
        try {
            Log::info('uploadDesignAndSendMessage called');

            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'message' => 'required|string',
                'sizes' => 'required|array',
                'quantities' => 'required|array',
            ]);

            $imageUrl = null;
            $imagePath = null;
            if ($request->file('image')) {
                $imagePath = $request->file('image')->store('designs', 'public');
                $imageUrl = Storage::url($imagePath);
                Log::info('Image uploaded: ' . $imageUrl);
            }

            // Create a new conversation
            $conversation = new Conversation();
            $conversation->user_id = Auth::id();
            $conversation->save();
            Log::info('Conversation created: ' . $conversation->convoID);

            // Create a new message
            $messageContent = $request->input('message');
            if ($imageUrl) {
                $messageContent .= '<br><img src="' . $imageUrl . '" alt="Design Image" style="max-width: 100%; height: auto;">';
            }

            $message = new Message([
                'messContent' => $messageContent,
                'messDate' => now(),
                'user_id' => Auth::id(),
                'convoID' => $conversation->convoID,
            ]);

            $message->save();
            Log::info('Message created: ' . $message->messID);

            // Update the conversation with the latest message ID
            $conversation->messID = $message->messID;
            $conversation->save();
            Log::info('Conversation updated with message ID: ' . $message->messID);

            // Create a new upload order
            $uploadOrder = new UploadOrder([
                'upName' => $request->file('image')->getClientOriginalName(),
                'upQuantity' => array_sum($request->input('quantities')),
                'upAmount' => 0,
                'user_id' => Auth::id(),
            ]);
            $uploadOrder->save();
            Log::info('Upload order created: ' . $uploadOrder->upID);

            // Attach sizes and quantities to the upload order
            $sizes = $request->input('sizes');
            $quantities = $request->input('quantities');

            foreach ($sizes as $index => $sizeName) {
                $size = Size::where('sizeName', $sizeName)->first();
                if ($size) {
                    $uploadOrder->sizes()->attach($size->sizeID, ['quantity' => $quantities[$index]]);
                    Log::info('Attached size: ' . $size->sizeID . ' with quantity: ' . $quantities[$index]);
                } else {
                    Log::warning('Size not found: ' . $sizeName);
                }
            }

            // Calculate the total amount
            $totalAmount = 0;
            foreach ($sizes as $index => $sizeName) {
                $size = Size::where('sizeName', $sizeName)->first();
                if ($size) {
                    $totalAmount += $size->sizePrice * $quantities[$index];
                }
            }

            // Update the upload order with the total amount
            $uploadOrder->upAmount = $totalAmount;
            $uploadOrder->save();
            Log::info('Upload order updated with total amount: ' . $totalAmount);

            // Create a new order
            $order = new Order([
                'orderTotal' => $totalAmount,
                'orderStatus' => 'Pending',
                'orderQuantity' => array_sum($quantities),
                'dateOrder' => now(),
                'user_id' => Auth::id(),
                'upID' => $uploadOrder->upID,
                'convoID' => $conversation->convoID,
            ]);
            $order->save();
            Log::info('Order created: ' . $order->orderID);

            // Generate the billing statement using the BillingStatementController
            app(BillingStatementController::class)->generate($order);

            // Dispatch the event to broadcast the conversation
            broadcast(new ConversationCreated($conversation));
            Log::info('ConversationCreated event broadcasted');

            return response()->json(['success' => true, 'redirectUrl' => route('chat', ['convoID' => $conversation->convoID])]);
        } catch (\Exception $e) {
            Log::error('Error in uploadDesignAndSendMessage: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return response()->json(['success' => false, 'message' => 'An error occurred while processing your request.'], 500);
        }
    }
}

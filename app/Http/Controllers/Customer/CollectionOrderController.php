<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Collection;
use App\Models\Size;
use App\Models\Conversation;
use App\Models\Message;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Customer\BillingStatementController;

class CollectionOrderController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'collectID' => 'required|exists:collections,collectID',
                'sizes' => 'required|array',
                'quantities' => 'required|array',
                'sizes.*' => 'required|exists:sizes,sizeID',
                'quantities.*' => 'required|integer|min:1',
            ]);

            $user = Auth::user();

            if (!$user) {
                return redirect()->route('login')->with('error', 'User not authenticated');
            }

            // Calculate the total amount
            $totalAmount = 0;
            foreach ($request->sizes as $index => $size_id) {
                $size = Size::findOrFail($size_id);
                $quantity = $request->quantities[$index];
                $totalAmount += $size->sizePrice * $quantity;
            }

            // Create a new conversation
            $conversation = Conversation::create([
                'user_id' => $user->user_id,
            ]);

            // Create a new order
            $order = Order::create([
                'user_id' => $user->user_id,
                'orderTotal' => $totalAmount,
                'orderStatus' => 'Pending',
                'orderQuantity' => array_sum($request->quantities),
                'dateOrder' => now(),
                'convoID' => $conversation->convoID,
                'collectID' => $request->collectID,
            ]);

            foreach ($request->sizes as $index => $size_id) {
                $quantity = $request->quantities[$index];
                $order->collections()->attach($request->collectID, [
                    'sizeID' => $size_id,
                    'quantity' => $quantity,
                ]);
            }

            // Send an automated message
            $message = new Message([
                'messContent' => 'Hi! I have an order',
                'messDate' => now(),
                'user_id' => $user->user_id,
                'convoID' => $conversation->convoID,
            ]);
            $message->save();

            $conversation->messID = $message->messID;
            $conversation->save();

            // Generate the billing statement using the BillingStatementController
            app(BillingStatementController::class)->generate($order);

            Log::info('Redirecting to chat', ['convoID' => $conversation->convoID]);

            return redirect()->route('chat', ['convoID' => $conversation->convoID]);
        } catch (\Exception $e) {
            Log::error('Error in store method: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while processing your request. Please try again later.');
        }
    }
}

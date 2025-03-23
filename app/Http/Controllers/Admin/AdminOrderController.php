<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\User;
use App\Events\OrderStatusUpdatedEvent;
use App\Models\Notification;  // If you're referencing your custom Notification model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;  // Make sure this line is outside of the namespace

class AdminOrderController extends Controller
{
    public function edit($id)
    {
        Log::info('Entering AdminOrderController@edit method with ID: ' . $id);

        $order = Order::findOrFail($id);
        Log::info('Editing order: ', $order->toArray()); // Log the order details being edited

        return view('editOrder', compact('order'));
    }

    public function update(Request $request, $orderId)
    {
        try {
            // Log the incoming request for debugging
            Log::info("Attempting to update order with ID: $orderId");

            // Validate incoming request
            $request->validate([
                'orderStatus' => 'required|string', // Ensure the status is a valid string
            ]);

            // Find the order by order ID
            $order = Order::find($orderId);
            if (!$order) {
                // Log the error for debugging
                Log::error("Order with ID $orderId not found.");
                return response()->json(['success' => false, 'message' => 'Order not found']);
            }

            // Log to check if the user_id exists and is valid
            Log::info("User ID for Order ID $orderId: {$order->user_id}");

            // Update the status of the order
            $oldStatus = $order->orderStatus;
            $order->orderStatus = $request->input('orderStatus');
            $order->save();

            // Log the status change
            Log::info("Order ID $orderId status changed from '$oldStatus' to '{$order->orderStatus}'");

            DB::enableQueryLog();

            // Ensure the user_id exists and send the notification
            if ($order->user_id) {
                $user = User::find($order->user_id);
                if ($user) {
                    Log::info("User found with ID {$user->user_id}. Sending notification.");

                    // Manually create a notification and save it to the database
                    $notification = new Notification();
                    $notification->notifContent = "Your order (ID: {$order->orderID}) status has been updated to: {$order->orderStatus}";
                    $notification->user_id = $order->user_id;  // Link the notification to the user
                    $notification->orderID = $order->orderID;  // Pass the order ID for reference
                    $notification->save(); // Save the notification to the database

                    // Log the executed queries
                    Log::info("Executed queries: ", DB::getQueryLog());

                    Log::info("Notification created for user ID {$user->user_id} about order status update.");
                } else {
                    Log::warning("No user found for user_id {$order->user_id}. Notification not created.");
                }
            } else {
                Log::warning("Order with ID $orderId does not have a valid user_id. Notification not created.");
            }

            broadcast(new OrderStatusUpdatedEvent($order));

            // Return success response
            return response()->json(['success' => true, 'message' => 'Order updated successfully']);
        } catch (QueryException $e) {
            // Log database error
            Log::error('Database error during order update', [
                'error' => $e->getMessage(),
                'orderId' => $orderId,
                'orderStatus' => $request->input('orderStatus')
            ]);
            return response()->json(['success' => false, 'message' => 'Database error occurred']);
        } catch (\Exception $e) {
            // Log unexpected error
            Log::error('Unexpected error during order update', [
                'error' => $e->getMessage(),
                'orderId' => $orderId,
                'orderStatus' => $request->input('orderStatus')
            ]);
            return response()->json(['success' => false, 'message' => 'An error occurred while updating the order']);
        }
    }


    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return response()->json(['success' => true, 'message' => 'Order deleted successfully.']);
    }

    public function show($id)
    {
        $order = Order::findOrFail($id);
        return response()->json($order);
    }
}

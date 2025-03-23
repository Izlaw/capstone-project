<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Events\OrderStatusUpdatedEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class ManageOrderController extends Controller
{
    // Show all orders (for both admin and employee)

    public function index()
    {
        $orders = Order::with('user', 'collection', 'customOrder', 'uploadOrder', 'customOrder.size')->get();
        return view('manageorder.index', compact('orders'));
    }

    // Show a specific order (view details)
    public function show($id)
    {
        $order = Order::findOrFail($id);
        return response()->json($order);
    }


    // Show the form to edit the order (admin or employee)
    public function edit($id)
    {
        Log::info('Entering ManageOrderController@edit method with ID: ' . $id);

        $order = Order::findOrFail($id);
        Log::info('Editing order: ', $order->toArray()); // Log the order details being edited

        return view('manageorder.edit', compact('order'));
    }

    // Update the order status (admin or employee)
    public function update(Request $request, $orderId)
    {
        try {
            Log::info("Attempting to update order with ID: $orderId");

            $request->validate([
                'orderStatus' => 'required|string',
            ]);

            $order = Order::find($orderId);
            if (!$order) {
                Log::error("Order with ID $orderId not found.");
                return response()->json(['success' => false, 'message' => 'Order not found']);
            }

            $oldStatus = $order->orderStatus;
            $order->orderStatus = $request->input('orderStatus');
            $order->save();

            Log::info("Order ID $orderId status changed from '$oldStatus' to '{$order->orderStatus}'");

            DB::enableQueryLog();

            if ($order->user_id) {
                $user = User::find($order->user_id);
                if ($user) {
                    Log::info("User found with ID {$user->user_id}. Sending notification.");

                    $notification = new Notification();
                    $notification->notifContent = "Your order (ID: {$order->orderID}) status has been updated to: {$order->orderStatus}";
                    $notification->user_id = $order->user_id;
                    $notification->orderID = $order->orderID;
                    $notification->save();

                    Log::info("Notification created for user ID {$user->user_id} about order status update.");
                } else {
                    Log::warning("No user found for user_id {$order->user_id}. Notification not created.");
                }
            }

            broadcast(new OrderStatusUpdatedEvent($order));

            return response()->json(['success' => true, 'message' => 'Order updated successfully']);
        } catch (QueryException $e) {
            Log::error('Database error during order update', [
                'error' => $e->getMessage(),
                'orderId' => $orderId,
                'orderStatus' => $request->input('orderStatus')
            ]);
            return response()->json(['success' => false, 'message' => 'Database error occurred']);
        } catch (\Exception $e) {
            Log::error('Unexpected error during order update', [
                'error' => $e->getMessage(),
                'orderId' => $orderId,
                'orderStatus' => $request->input('orderStatus')
            ]);
            return response()->json(['success' => false, 'message' => 'An error occurred while updating the order']);
        }
    }

    // Delete an order (admin or employee)
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return response()->json(['success' => true, 'message' => 'Order deleted successfully.']);
    }
}

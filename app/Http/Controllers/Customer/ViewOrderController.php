<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Conversation;
use Illuminate\Support\Facades\Log;

class ViewOrderController extends Controller
{
    public function viewOrders()
    {
        $orders = Order::with(['sizes', 'customOrder', 'uploadOrder', 'user', 'conversation'])->get();

        // Log the orders object for debugging
        Log::info($orders);

        return view('customerui.vieworder', [
            'orders' => $orders,
        ]);
    }
}

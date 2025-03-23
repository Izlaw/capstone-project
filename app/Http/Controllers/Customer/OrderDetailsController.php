<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class OrderDetailsController extends Controller
{
    public function showOrderDetails($orderId)
    {
        try {
            // Log for debugging
            Log::info('Fetching order details for Order ID: ' . $orderId);

            // Attempt to find the order by its ID with related models
            $order = Order::with(['collection', 'customOrder', 'uploadOrder'])->find($orderId);

            // If order is not found, log it and redirect
            if (!$order) {
                Log::warning('Order not found for Order ID: ' . $orderId);
                return redirect()->route('vieworder')->with('error', 'Order not found');
            }

            // Log the result of the order fetch
            Log::info('Order found:', ['order' => $order]);

            // Generate the billing statement filename with 'Billing-Statement-{orderID}.pdf'
            $fileName = 'Billing-Statement-' . $order->orderID . '.pdf';
            $billingFilePath = 'orders/billingstatements/' . $fileName;

            // Log the file path
            Log::info('Checking file path: ' .  $billingFilePath);

            // Check if the billing statement file exists
            $fileExists = Storage::disk('public')->exists($billingFilePath);
            Log::info('File exists: ' . ($fileExists ? 'Yes' : 'No'));

            // Pass data to the view
            return view('customerui.orderdetails', compact('order', 'billingFilePath', 'fileExists'));
        } catch (\Exception $e) {
            // Log the exception
            Log::error('Error fetching order details: ' . $e->getMessage());

            // Redirect with an error message
            return redirect()->route('vieworder')->with('error', 'An error occurred while fetching the order details.');
        }
    }
}

<?php

namespace App\Http\Controllers\Customer;

use App\Models\Order;
use App\Models\Size;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class BillingStatementController extends Controller
{
    /**
     * Generate a billing statement for a given order.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function generate(Order $order)
    {
        try {
            Log::info('Starting billing statement generation for order ID: ' . $order->orderID);

            // Retrieve customer information
            $customer = $order->user;
            $firstName = $customer->first_name;
            $lastName = $customer->last_name;
            $customerAddress = $customer->address;
            Log::info('Customer data retrieved', [
                'first_name' => $firstName,
                'last_name'  => $lastName,
                'email'      => $customer->email,
                'address'    => $customerAddress,
            ]);

            if ($order->upID) {
                // Upload order: get sizes from the upload order relationship
                $uploadOrder = $order->uploadOrder;
                $sizes = $uploadOrder->sizes;
                Log::debug('Upload order sizes', $sizes->toArray());
                $data = compact('order', 'uploadOrder', 'sizes', 'firstName', 'lastName', 'customerAddress');
            } elseif ($order->collectID) {
                // Collection order: use the collectionSizes relationship to get Size models (without join)
                $collection = $order->collection;
                $sizes = $order->collectionSizes;
                Log::debug('Collection order sizes (collectionSizes): ', $sizes->toArray());
                $data = compact('order', 'collection', 'sizes', 'firstName', 'lastName', 'customerAddress');
            } else {
                // Default case: for custom orders, use customOrder sizes if available
                $sizes = $order->customOrder ? $order->customOrder->sizes : collect([]);
                Log::debug('Custom order sizes', $sizes->toArray());
                $data = compact('order', 'sizes', 'firstName', 'lastName', 'customerAddress');
            }

            // Generate the PDF using the billingstatement view and the prepared data
            $pdf = Pdf::loadView('billingstatement', $data);

            // Generate a unique filename using orderID
            $fileName = 'Billing-Statement-' . $order->orderID . '.pdf';
            $filePath = 'orders/billingstatements/' . $fileName;

            // Store the PDF in the specified directory (public/orders/billingstatements)
            Storage::disk('public')->put($filePath, $pdf->output());
            Log::info('Billing statement PDF stored successfully', ['file_path' => $filePath]);

            // Get the URL to the file in the public directory
            $fileUrl = Storage::url($filePath);

            return response()->json(['fileUrl' => $fileUrl]);
        } catch (\Exception $e) {
            Log::error('Error generating billing statement:', [
                'message'  => $e->getMessage(),
                'order_id' => $order->orderID,
            ]);
            return response()->json(['error' => 'Failed to generate billing statement'], 500);
        }
    }
}

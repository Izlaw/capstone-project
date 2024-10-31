<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CustomOrder; // Import your model
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CustomOrderController extends Controller
{
    public function show($id)
    {
        // Retrieve the custom order from the database using the ID
        $order = CustomOrder::findOrFail($id);

        // Return the view and pass the order data
        return view('previeworder', ['order' => $order]);
    }

    public function generateQRCode(Request $request)
    {
        // Validate the request data
        try {
            $validatedData = $request->validate([
                'color' => 'required|string',
                'collarType' => 'required|string',
                'size_xs' => 'nullable|integer|min:0',
                'size_s' => 'nullable|integer|min:0',
                'size_m' => 'nullable|integer|min:0',
                'size_l' => 'nullable|integer|min:0',
                'size_xl' => 'nullable|integer|min:0',
            ]);
    
            // Provide default values if sizes are not included in the request
            $validatedData['size_xs'] = (int) ($validatedData['size_xs'] ?? 0);
            $validatedData['size_s'] = (int) ($validatedData['size_s'] ?? 0);
            $validatedData['size_m'] = (int) ($validatedData['size_m'] ?? 0);
            $validatedData['size_l'] = (int) ($validatedData['size_l'] ?? 0);
            $validatedData['size_xl'] = (int) ($validatedData['size_xl'] ?? 0);
    
            // Calculate total quantity
            $totalQuantity = $validatedData['size_xs'] + $validatedData['size_s'] + $validatedData['size_m'] + $validatedData['size_l'] + $validatedData['size_xl'];
    
            // Log the validated data and total quantity
            \Log::info('Validated Data:', $validatedData);
            \Log::info('Total Quantity:', ['total' => $totalQuantity]);
    
            // Save the customization data to the database
            $order = CustomOrder::create([
                'color' => $validatedData['color'],
                'collar_type' => $validatedData['collarType'],
                'size_xs' => $validatedData['size_xs'],
                'size_s' => $validatedData['size_s'],
                'size_m' => $validatedData['size_m'],
                'size_l' => $validatedData['size_l'],
                'size_xl' => $validatedData['size_xl'],
                'total' => $totalQuantity, // Use calculated total
            ]);
    
            // Generate a dynamic URL with color and size parameters
            $previewUrl = route('previeworder', [
                'id' => $order->id,
                'color' => $order->color,
                'collar' => $order->collar_type,
                'size_xs' => $order->size_xs,
                'size_s' => $order->size_s,
                'size_m' => $order->size_m,
                'size_l' => $order->size_l,
                'size_xl' => $order->size_xl,
            ]);
    
            // Generate the QR code
            $qrCode = \QrCode::size(200)->generate($previewUrl);
    
            // Return the QR code view
            return view('qrcode', ['qrCode' => $qrCode]);
        } catch (\Exception $e) {
            // Log the exception message
            \Log::error('Error in QR Code Generation:', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
    

    public function generateBillingStatement(Request $request)
    {
        try {
            $customizations = $request->input(); // Get customizations from the request
    
            // Assuming user is logged in, you can fetch user details
            $customerName = $request->user()->name; 
            $customerEmail = $request->user()->email;
            $customerAddress = $request->user()->address;
    
            // Retrieve size quantities, defaulting to 0 if not set
            $size_xs = $customizations['size_xs'] ?? 0;
            $size_s = $customizations['size_s'] ?? 0;
            $size_m = $customizations['size_m'] ?? 0;
            $size_l = $customizations['size_l'] ?? 0;
            $size_xl = $customizations['size_xl'] ?? 0;
    
            // Example prices per size
            $price_xs = 200; // Example price
            $price_s = 250; // Example price
            $price_m = 300; // Example price
            $price_l = 350; // Example price
            $price_xl = 400; // Example price
    
            // Calculate total amount
            $totalAmount = ($size_xs * $price_xs) +
                           ($size_s * $price_s) +
                           ($size_m * $price_m) +
                           ($size_l * $price_l) +
                           ($size_xl * $price_xl);
    
            // Generate billing statement as a PDF
            $pdf = PDF::loadView('billingstatement', compact('customerName', 'customerEmail', 'customerAddress', 'size_xs', 'size_s', 'size_m', 'size_l', 'size_xl', 'price_xs', 'price_s', 'price_m', 'price_l', 'price_xl', 'totalAmount'))
                        ->setOptions(['defaultFont' => 'Arial']);

            // Return PDF for download
            return response()->stream(
                function () use ($pdf) {
                    echo $pdf->output(); // Output PDF directly
                },
                200,
                [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'attachment; filename="Billing-Statement.pdf"',
                ]
            );
    
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Error generating billing statement: ' . $e->getMessage());
    
            // Return error as JSON
            return response()->json(['error' => 'Unable to generate billing statement. Please try again later.'], 500);
        }
    }
}

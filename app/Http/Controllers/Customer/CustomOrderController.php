<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\CustomOrder;
use App\Models\Size;
use App\Models\User;
use App\Models\Conversation;
use App\Models\Order;
use App\Events\MessageSent;
use App\Models\Message;
use App\Models\CustomModel;
use App\Events\ConversationCreated;
use App\Events\OrderCreated;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Controllers\BillingStatementController;

class CustomOrderController extends Controller
{
    public function customize(Request $request)
    {
        // Get the selected model type
        $model = $request->query('model');

        $sizes = Size::all();
        // Pass the selected model type to the view
        return view('customerui.customize', compact('model', 'sizes'));
    }

    public function previewOrder($id)
    {
        // Use 'customModel' instead of 'model'
        $order = CustomOrder::findOrFail($id);

        // Return the view and pass the order data
        return view('previeworder', [
            'order' => $order,
        ]);
    }

    public function generateQRCode(Request $request)
    {
        Log::info('Incoming request data:', $request->all());

        try {
            // First validate the base requirements that don't change
            $baseValidation = [
                'colors' => 'required|array',
                'colors.backColor' => 'required|string',
                'colors.frontColor' => 'required|string',
                'colors.sleevesColor' => 'required|string',
                'colors.collarColor' => 'required|string',
                'sizes' => 'required|array|min:1',
                'sizes.*.sizeID' => 'required|exists:sizes,sizeID',
                'sizes.*.quantity' => 'required|integer|min:1',
                'fabric_type' => 'required|string',
                'custom_fabric_type' => 'nullable|string',
                'collectID' => 'sometimes|exists:collections,collectID',
                'upID' => 'sometimes|exists:upload_orders,upID',
            ];

            // Modify text validation - accept either a string or array
            $baseValidation['text'] = 'nullable';

            // Validate the request with our modified validation rules
            $validatedData = $request->validate($baseValidation);

            // Process the text field depending on its format
            $processedTextData = null;

            if (isset($validatedData['text'])) {
                if (is_string($validatedData['text'])) {
                    // Text is a string of concatenated JSON objects
                    Log::info('Text is provided as a string, processing multiple text customizations');

                    // Parse the concatenated JSON string into an array of objects
                    $processedTextData = $this->parseMultipleTextCustomizations($validatedData['text']);
                    Log::info('Processed text data:', ['data' => $processedTextData]);
                } elseif (is_array($validatedData['text']) && isset($validatedData['text']['text'])) {
                    // It's a single text object in the original format
                    Log::info('Text is provided as a single object');
                    $processedTextData = [$validatedData['text']];
                }
            }

            // Determine the fabric type to use
            $fabricType = $validatedData['fabric_type'] === 'custom' && !empty($validatedData['custom_fabric_type'])
                ? $validatedData['custom_fabric_type']
                : $validatedData['fabric_type'];

            // Calculate total amount and custom quantity
            $totalAmount = 0;
            $customQuantity = 0;
            foreach ($validatedData['sizes'] as $sizeData) {
                $size = Size::findOrFail($sizeData['sizeID']);
                $totalAmount += $size->sizePrice * $sizeData['quantity'];
                $customQuantity += $sizeData['quantity'];
            }
            Log::info('Calculated total amount:', ['totalAmount' => $totalAmount]);
            Log::info('Calculated custom quantity:', ['customQuantity' => $customQuantity]);

            // Create the custom order including text customization data
            $customOrder = CustomOrder::create([
                'colors' => $validatedData['colors'],
                'text' => $processedTextData, // Store the processed text data
                'fabric_type' => $fabricType,
                'user_id' => $request->user()->user_id,
                'totalAmount' => $totalAmount,
                'customQuantity' => $customQuantity,
            ]);
            Log::info('Created custom order:', ['data' => json_encode($customOrder->toArray(), JSON_PRETTY_PRINT)]);

            // Save the sizes and quantities in the pivot table
            foreach ($validatedData['sizes'] as $sizeData) {
                $customOrder->sizes()->attach($sizeData['sizeID'], ['quantity' => $sizeData['quantity']]);
            }

            // Create a new conversation for each order
            $conversation = Conversation::create([
                'user_id' => $request->user()->user_id,
                'messID' => null
            ]);
            Log::info('Conversation created:', ['convoID' => $conversation->convoID]);

            // Create the order entry
            $order = Order::create([
                'orderTotal' => $totalAmount,
                'orderStatus' => 'Pending',
                'orderQuantity' => $customQuantity,
                'dateOrder' => now(),
                'user_id' => $customOrder->user_id,
                'customID' => $customOrder->customID,
                'collectID' => $validatedData['collectID'] ?? null,
                'upID' => $validatedData['upID'] ?? null,
                'convoID' => $conversation->convoID,
            ]);

            // Generate the preview URL for the custom order
            $previewUrl = route('previeworder', ['id' => $customOrder->customID]);
            Log::info('Generated preview URL:', ['url' => $previewUrl]);

            // Generate the QR code image
            $qrCodeImage = QrCode::format('png')->size(300)->errorCorrection('H')->generate($previewUrl);
            $filePath = 'orders/qrcodes/QRcode-order-' . $customOrder->customID . '.png';
            Storage::disk('public')->put($filePath, $qrCodeImage);
            Log::info('QR Code saved:', ['path' => $filePath]);

            // Return the view with necessary data
            return view('qrcode', [
                'qrCode' => asset('storage/' . $filePath),
                'order' => $customOrder,
                'conversation' => $conversation,
            ]);
        } catch (\Exception $e) {
            Log::error('Error in QR Code Generation:', [
                'message' => $e->getMessage(),
                'request_data' => $request->all(),
            ]);
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    /**
     * Parse a string of concatenated JSON objects into an array of PHP objects
     * 
     * @param string $jsonString The concatenated JSON string
     * @return array An array of parsed objects
     */
    private function parseMultipleTextCustomizations($jsonString)
    {
        $result = [];
        $length = strlen($jsonString);
        $currentPos = 0;
        $bracketCount = 0;
        $startPos = null;

        // Iterate through the string character by character
        for ($i = 0; $i < $length; $i++) {
            $char = $jsonString[$i];

            if ($char === '{') {
                $bracketCount++;
                if ($bracketCount === 1) {
                    // Start of a new JSON object
                    $startPos = $i;
                }
            } elseif ($char === '}') {
                $bracketCount--;
                if ($bracketCount === 0 && $startPos !== null) {
                    // End of the current JSON object
                    $jsonObject = substr($jsonString, $startPos, ($i - $startPos) + 1);

                    // Decode the JSON object and add it to the result
                    $decodedObject = json_decode($jsonObject, true);
                    if ($decodedObject !== null) {
                        $result[] = $decodedObject;
                    } else {
                        Log::error('Failed to decode JSON object:', ['jsonObject' => $jsonObject, 'error' => json_last_error_msg()]);
                    }

                    $startPos = null;
                }
            }
        }

        return $result;
    }

    public function sendQRCodeToEmployee(Request $request)
    {
        $userID = Auth::id();
        $order = Order::where('user_id', $userID)->orderBy('orderID', 'desc')->first();

        if (!$order) {
            Log::error('Order not found for the user.', ['user_id' => $userID]);
            return response()->json(['error' => 'Order not found'], 404);
        }

        $filePath = 'orders/qrcodes/QRcode-order-' . $order->customID . '.png';

        if (!file_exists(storage_path('app/public/' . $filePath))) {
            Log::error('QR code file not found.', ['file_path' => $filePath]);
            return response()->json(['error' => 'QR code not found'], 404);
        }

        $randomEmployee = User::where('role', 'employee')->inRandomOrder()->first();

        if (!$randomEmployee) {
            Log::error('No employee available to send the QR code.');
            return response()->json(['error' => 'No employee available'], 400);
        }

        Log::info('Customer redirected to a random employee', [
            'customer_id' => $userID,
            'random_employee_id' => $randomEmployee->user_id,
        ]);

        $conversation = Conversation::where('user_id', $userID)
            ->whereNull('messID')
            ->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'user_id' => $userID,
                'messID' => null,
            ]);
        }

        $messageContent = "Hello! This is my order.";
        $message = Message::create([
            'messContent' => $messageContent,
            'messDate' => now(),
            'user_id' => $userID,
            'convoID' => $conversation->convoID,
            'type' => 'text',
        ]);

        $imageUrl = asset('storage/' . $filePath);
        $imageMessage = Message::create([
            'messContent' => $imageUrl,
            'messDate' => now(),
            'user_id' => $userID,
            'convoID' => $conversation->convoID,
            'type' => 'image',
        ]);

        $conversation->update(['messID' => $imageMessage->messID]);

        broadcast(new MessageSent($randomEmployee, $imageMessage));

        Log::info('Message sent to employee', [
            'customer_id' => $userID,
            'employee_id' => $randomEmployee->user_id,
            'conversation_id' => $conversation->convoID,
        ]);

        return redirect()->route('chat', ['convoID' => $conversation->convoID]);
    }

    public function downloadQRCode($customID)
    {
        Log::info('Download QR Code requested for order ID:', ['id' => $customID]);

        $order = CustomOrder::findOrFail($customID);
        Log::info('Retrieved order data:', ['order' => $order->toArray()]);

        $previewUrl = route('previeworder', ['id' => $order->customID]);
        Log::info('Generated Preview URL for QR Code:', ['previewUrl' => $previewUrl]);

        try {
            $qrCodeImage = QrCode::format('png')->size(300)->errorCorrection('H')->generate($previewUrl);
            Log::info('QR Code generated successfully.');
            return response()->stream(function () use ($qrCodeImage) {
                echo $qrCodeImage;
            }, 200, [
                'Content-Type' => 'image/png',
                'Content-Disposition' => 'attachment; filename="QRCode.png"',
            ]);
        } catch (\Exception $e) {
            Log::error('Error generating QR Code:', [
                'message' => $e->getMessage(),
                'order_id' => $customID,
            ]);
            return response()->json(['error' => 'QR Code generation failed'], 500);
        }
    }

    public function generateBillingStatement(Request $request)
    {
        try {
            Log::info('Starting billing statement generation.');

            if (!$request->user()) {
                Log::error('User is not authenticated.');
                return response()->json(['error' => 'User not authenticated.'], 401);
            }

            Log::info('Authenticated User ID:', ['user_id' => $request->user()->user_id]);

            $validatedData = $request->validate([
                'colors' => 'required|array',
                'colors.backColor' => 'required|string',
                'colors.frontColor' => 'required|string',
                'colors.sleevesColor' => 'required|string',
                'colors.collarColor' => 'required|string',
                'sizes' => 'required|array|min:1',
                'sizes.*.sizeID' => 'required|exists:sizes,sizeID',
                'sizes.*.quantity' => 'required|integer|min:1',
                'fabric_type' => 'required|string',
            ]);

            Log::info('Request data validated successfully', $validatedData);

            $customOrder = CustomOrder::with('sizes')->where('user_id', $request->user()->user_id)
                ->where('colors', json_encode($validatedData['colors']))
                ->where('fabric_type', $validatedData['fabric_type'])
                ->first();

            if (!$customOrder) {
                Log::error('Custom order not found for user', ['user_id' => $request->user()->user_id]);
                return response()->json(['error' => 'Custom order not found.'], 404);
            }

            Log::info('Custom order retrieved successfully', ['customOrder' => $customOrder->toArray()]);

            $totalAmount = 0;
            foreach ($validatedData['sizes'] as $sizeData) {
                $size = Size::findOrFail($sizeData['sizeID']);
                $totalAmount += $size->sizePrice * $sizeData['quantity'];
            }
            Log::info('Total amount calculated', ['totalAmount' => $totalAmount]);

            $customer = $request->user();
            $firstName = $customer->first_name;
            $lastName = $customer->last_name;
            $customerAddress = $customer->address;
            Log::info('Customer data retrieved', [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $customer->email,
                'address' => $customerAddress,
            ]);

            $orders = $customOrder->orders;
            if ($orders->isEmpty()) {
                Log::error('No related orders found for Custom Order', ['customID' => $customOrder->customID]);
                return response()->json(['error' => 'No related orders found for Custom Order'], 404);
            }
            $order = $orders->first();
            $orderID = $order->orderID;

            if ($order->collectID) {
                $sizes = $order->collections()->withPivot('sizeID', 'quantity')->get();
                foreach ($sizes as $size) {
                    $size->pivot->sizePrice = Size::find($size->pivot->sizeID)->sizePrice;
                }
            } else {
                $sizes = $customOrder->sizes;
            }

            $uploadOrder = $customOrder->uploadOrder ?? null;

            $pdf = Pdf::loadView('billingstatement', [
                'order' => $order,
                'uploadOrder' => $uploadOrder,
                'sizes' => $sizes,
                'firstName' => $firstName,
                'lastName' => $lastName,
                'customerAddress' => $customerAddress,
                'totalAmount' => $totalAmount,
            ]);

            $fileName = 'Billing-Statement-' . $orderID . '.pdf';
            $filePath = 'orders/billingstatements/' . $fileName;

            Storage::disk('public')->put($filePath, $pdf->output());

            Log::info('Billing statement PDF stored successfully', ['file_path' => $filePath]);

            $fileUrl = Storage::url($filePath);

            return response()->json(['fileUrl' => $fileUrl]);
        } catch (\Exception $e) {
            Log::error('Error generating billing statement:', [
                'message' => $e->getMessage(),
                'request_data' => $request->all()
            ]);
            return response()->json(['error' => 'Failed to generate billing statement'], 500);
        }
    }
}

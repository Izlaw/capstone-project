<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CustomOrder;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

// User controllers
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\User\ChatController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\LoginController;
use App\Http\Controllers\User\ProfileController; 

// Customer controllers
use App\Http\Controllers\Customer\AddOrderController;
use App\Http\Controllers\Customer\AddCustomOrderController;
use App\Http\Controllers\Customer\CustomizeTShirtController;
use App\Http\Controllers\Customer\CustomOrderController;
use App\Http\Controllers\Customer\FAQController;
use App\Http\Controllers\Customer\OrderDetailsController;
use App\Http\Controllers\Customer\ViewCollectionsController;
use App\Http\Controllers\Customer\ViewOrderController;

// Employee controllers
use App\Http\Controllers\Employee\ManageOrderController;
use App\Http\Controllers\Employee\AssistCustomerController;

// Admin controllers

/*
|---------------------------------------------------------------------------
| Web Routes
|---------------------------------------------------------------------------
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will be
| assigned to the "web" middleware group. Make something great!
|---------------------------------------------------------------------------
*/

/*
Route syntax: 
Route::('/url', [Controller::class, 'method'])->name('route.name')->middleware('middleware');
*/

// Authentication routes
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Register routes
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);

// Redirect based on user role when accessing home
Route::get('/home', function () {
    $user = Auth::user(); // Get the authenticated user
    $user = Auth::user(); // Get the authenticated user

    // Check if a user is authenticated
    if ($user) {
        // Check if a user is authenticated
    if ($user) {
        if ($user->role == 'employee') {
                return redirect()->route('employee.dashboard');
            } elseif ($user->role == 'admin') {
                return redirect()->route('admin.dashboard');
        }
        }
    }

    // Redirect to customer UI for unauthenticated users or other roles
    // Redirect to customer UI for unauthenticated users or other roles
    return redirect()->route('customerui.home');
})->name('home');

// Public routes (accessible to guests and registered users)
Route::get('/', function () {
    return view('customerui.home');
})->name('customerui.home');

Route::get('/addorder', [AddOrderController::class, 'index'])->name('addorder');
Route::get('/addcustomorder', [AddOrderController::class, 'askGender'])->name('addcustomorder');
Route::get('/addcustomorder/{gender}', [AddOrderController::class, 'addCustomOrder'])->name('addcustomorder');
Route::get('/faq', [FAQController::class, 'index'])->name('faq');


// Routes that require authentication
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Routes that require the 'customer' role
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/ViewOrder', [ViewOrderController::class, 'index'])->name('vieworder');
    Route::get('/viewcollections', [ViewCollectionsController::class, 'index'])->name('viewcollections');
    Route::get('/orderDetails', [orderDetailsController::class, 'index'])->name('orderDetails');
    Route::get('/uploadorder', [AddOrderController::class, 'uploadCustomOrder'])->name('uploadorder');


    // 3D model
    Route::get('/customizetshirt', function () {
        return view('customerui.customizetshirt');
    })->name('customizetshirt');
});

// Employee routes
Route::middleware(['auth', 'role:employee'])->group(function () {
    Route::get('/employeedashboard', function () {
        return view('employeeui.empdboard');
    })->name('employee.dashboard');
    Route::get('/manageorder', [ManageOrderController::class, 'index'])->name('employeeui.manageorder');
    Route::get('/assistcustomer', [AssistCustomerController::class, 'index'])->name('employeeui.assistcustomer');
    Route::get('/assistcustomer', [AssistCustomerController::class, 'showConversations'])->name('employeeui.assistcustomer');
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admindashboard', function () {
        return view('adminui.admindboard');
    })->name('admin.dashboard');
});

// Access denied route
Route::get('/access-denied', function () {
    return view('access-denied');
})->name('access-denied');

// Main Chat Route - Redirects to a random employee if no recipient is provided
Route::get('/chat', function () {
    $user = Auth::user();

    if ($user->role == 'customer') {
        $employees = User::where('role', 'employee')->get();
        if ($employees->isEmpty()) {
            abort(404, 'No employees found');
        }
        $randomEmployee = $employees->random();
        return redirect()->route('chat.recipient', ['recipient' => $randomEmployee->user_id]);
    }

    $customers = User::whereHas('messages', function ($query) use ($user) {
        $query->where('user_id', $user->user_id);
    })->get();

    return view('employeeui.assistcustomer', ['conversations' => $customers]);
})->middleware('auth')->name('chat');

// Route for specific recipient conversation
Route::get('/chat/{recipient}', [AssistCustomerController::class, 'showChat'])->middleware('auth')->name('chat.recipient');

// Send Message Route
Route::post('/send-message', [ChatController::class, 'sendMessage'])->middleware('auth');

Route::post('/qrcode', function (Request $request) {
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
        Log::info('Validated Data:', $validatedData);
        Log::info('Total Quantity:', ['total' => $totalQuantity]);

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
        $qrCode = QrCode::size(200)->generate($previewUrl);

        // Return the QR code view
        return view('qrcode', ['qrCode' => $qrCode]);
    } catch (\Exception $e) {
        // Log the exception message
        Log::error('Error in QR Code Generation:', ['message' => $e->getMessage()]);
        return response()->json(['error' => 'Internal Server Error'], 500);
    }
})->name('qrcode');

// Define route to preview a specific order
Route::get('/previeworder/{id}', [CustomOrderController::class, 'show'])->name('previeworder');
<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CustomOrder;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

// Essential controllers
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ChatController;

// Customer routes
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AddOrderController;
use App\Http\Controllers\UploadOrderController;
use App\Http\Controllers\UploadOrderMaleController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UploadOrderFemaleController;
use App\Http\Controllers\ViewOrderController;
use App\Http\Controllers\ViewCollectionsController;
use App\Http\Controllers\CustomizeTShirtController;
use App\Http\Controllers\orderDetailsController;
use App\Http\Controllers\CustomizeTShirtController;
use App\Http\Controllers\CustomOrderController;

// Employee controllers
use App\Http\Controllers\ManageOrderController;
use App\Http\Controllers\AssistCustomerController;

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
Route::get('/uploadorder', [UploadOrderController::class, 'index'])->name('uploadorder');
Route::get('/uploadordermale', [UploadOrderMaleController::class, 'index'])->name('uploadordermale');
Route::get('/uploadorderfemale', [UploadOrderFemaleController::class, 'index'])->name('uploadorderfemale');
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

// QR Code Generation Route
Route::get('/qrcode', function (Request $request) {
    // Validate the request data
    $validatedData = $request->validate([
        'color' => 'required|string',
        'collarType' => 'required|string',
    ]);

    // Save the customization data to the database
    $order = CustomOrder::create([
        'color' => $validatedData['color'],
        'collar_type' => $validatedData['collarType'],
    ]);

    // Generate a dynamic URL with color parameter
    $previewUrl = route('previeworder', ['id' => $order->id, 'color' => $order->color]);
    $qrCode = QrCode::size(200)->generate($previewUrl);

    // Return the QR code view
    return view('qrcode', ['qrCode' => $qrCode]);
})->name('qrcode');


// Define route to preview a specific order
Route::get('/previeworder/{id}', [CustomOrderController::class, 'show'])->name('previeworder');
<?php

use Illuminate\Support\Facades\Route;
use App\Models\User; 

// essential controllers
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ChatController;

// customer routes 
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AddOrderController;
use App\Http\Controllers\UploadOrderController;
use App\Http\Controllers\UploadOrderMaleController;
use App\Http\Controllers\CustomerSupportController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UploadOrderFemaleController;
use App\Http\Controllers\ViewOrderController;

// employee controllers
use App\Http\Controllers\ManageOrderController;
use App\Http\Controllers\AssistCustomerController;

// admin controllers


/*
|---------------------------------------------------------------------------
| Web Routes
|---------------------------------------------------------------------------
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will be
| assigned to the "web" middleware group. Make something great!
|---------------------------------------------------------------------------
*/

// Authentication routes
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Regisater routes
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);

//Default route
Route::get('/', function () {
    return view('customerui.home');
});

// Public routes (accessible to guests and registered users)
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/addorder', [AddOrderController::class, 'index'])->name('addorder');
Route::get('/uploadorder', [UploadOrderController::class, 'index'])->name('uploadorder');
Route::get('/uploadordermale', [UploadOrderMaleController::class, 'index'])->name('uploadordermale');
Route::get('/uploadorderfemale', [UploadOrderFemaleController::class, 'index'])->name('uploadorderfemale');

// Routes that require authentication
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Routes that require the 'customer' role
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/customersupport', [CustomerSupportController::class, 'index'])->name('customersupport');
    Route::get('/vieworder', [ViewOrderController::class, 'index'])->name('vieworder');
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
Route::get('/chat/{recipient}', function ($recipient) {
    $recipientUser = User::find($recipient);
    if (!$recipientUser) {
        abort(404, 'Recipient not found');
    }
    return view('chat', ['recipient' => $recipientUser]);
})->middleware('auth')->name('chat.recipient');

// Send Message Route
Route::post('/send-message', [ChatController::class, 'sendMessage'])->middleware('auth');


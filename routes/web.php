<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CustomOrder;
use App\Models\Message;
use App\Events\MessageSent;
use App\Events\ExampleEvent;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

use App\Http\Controllers\PusherAuthController;
use App\Http\Controllers\ViewController;

// Debugging
use App\Http\Controllers\LogController;

// User controllers
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\User\ChatController;
use App\Http\Controllers\User\LoginController;
use App\Http\Controllers\User\ProfileController;

// Customer controllers
use App\Http\Controllers\Customer\CustomOrderController;
use App\Http\Controllers\Customer\CollectionOrderController;
use App\Http\Controllers\Customer\UploadOrderController;
use App\Http\Controllers\Customer\FAQController;
use App\Http\Controllers\Customer\OrderDetailsController;
use App\Http\Controllers\Customer\ViewOrderController;

// Staff controllers
use App\Http\Controllers\ManageOrderController;

// Employee controllers
use App\Http\Controllers\Employee\AssistCustomerController;
use App\Http\Controllers\Employee\EmployeeController;

// Admin controllers
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ManageEmployeeController;

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

// Debugging
Route::post('/log', [LogController::class, 'logMessage'])->name('log.message');

Broadcast::routes();
// Authentication Routes
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Register Routes
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);

// Home route to redirect based on user role
Route::get('/home', [ViewController::class, 'redirectUserToHome'])->name('home');

// Public Routes (Accessible to guests and registered users)
Route::get('/', function () {
    return view('customerui.home');
})->name('customerui.home');

// Guest Routes (accessible to everyone)
Route::get('/addorder', [ViewController::class, 'showaddOrder'])->name('addorder');
Route::get('/addcustomorder', [ViewController::class, 'askGender'])->name('addcustomorder');
Route::get('/addcustomorder', [ViewController::class, 'addCustomOrder'])->name('addcustomorder');
Route::get('/faq', [ViewController::class, 'showFaq'])->name('faq');

// Routes that require authentication (for authenticated users only)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Routes that require the 'customer' role
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/vieworder', [ViewOrderController::class, 'viewOrders'])->name('vieworder');
    Route::get('/viewcollections', [ViewController::class, 'showViewCollections'])->name('viewcollections');
    Route::get('/orderdetails/{orderId}', [OrderDetailsController::class, 'showOrderDetails'])->name('orderdetails');
    Route::get('/uploadorder', [ViewController::class, 'uploadCustomOrder'])->name('uploadorder');
    Route::get('/customize', [CustomOrderController::class, 'customize'])->name('customize');
    Route::post('/collection-order', [CollectionOrderController::class, 'store'])->name('collection.order.store');
    Route::post('/qrcode', [CustomOrderController::class, 'generateQRCode'])->name('qrcode');
    Route::get('/download-qrcode/{customID}', [CustomOrderController::class, 'downloadQRCode'])->name('download.qrcode');
    Route::post('/generate-billing-statement', [CustomOrderController::class, 'generateBillingStatement']);
    Route::get('/previeworder/{id}', [CustomOrderController::class, 'previewOrder'])->name('previeworder');
    Route::get('/send-qrcode', [CustomOrderController::class, 'sendQRCodeToEmployee'])->name('sendQRCodeToEmployee');
    Route::get('/supportchat/{convoID}', [ChatController::class, 'showChat'])->name('supportchat');
    Route::post('/upload-design-and-send-message', [UploadOrderController::class, 'uploadDesignAndSendMessage'])->name('upload-design-and-send-message');
});

// Staff routes
Route::middleware(['auth'])->group(function () {
    Route::resource('manageOrder', ManageOrderController::class);
});

// Employee Routes (Require 'employee' role)
Route::middleware(['auth', 'role:employee'])->group(function () {
    Route::get('/employeedashboard', [ViewController::class, 'showEmpDboard'])->name('employee.dashboard');
    Route::get('/manageorder', [EmployeeController::class, 'showEmpManageOrder'])->name('empManageOrder');
    Route::get('/empmanagecollections', [ViewController::class, 'showEmpManageCollections'])->name('empmanagecollections');
    Route::get('/assistcustomer', [AssistCustomerController::class, 'showConversations'])->name('assistcustomer.index');
    Route::get('/helpdesk/{convoID}', [AssistCustomerController::class, 'showChat'])->name('assistcustomer.show');
});

// Admin Routes (Require 'admin' role)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admindashboard', [ViewController::class, 'showAdminDboard'])->name('admin.dashboard');
    Route::get('/adminprices', [ViewController::class, 'showAdminPrices'])->name('adminPrices');
    Route::post('/update-sizes', [AdminController::class, 'updateSizes'])->name('updateSizes');
    Route::get('/adminorder', [ViewController::class, 'showAdminManageOrder'])->name('adminOrder');
    Route::get('/manageemployees', [ViewController::class, 'showManageEmployees'])->name('manageEmployees');
    Route::get('/admincollect', [ViewController::class, 'showAdminCollections'])->name('adminCollect');
});

// Access Denied route
Route::get('/access-denied', function () {
    return view('access-denied');
})->name('access-denied');

// Chat Routes
Route::get('/asksupport/{convoID}', [FAQController::class, 'askSupport'])->name('askSupport');
Route::get('/chat/{convoID}', [ChatController::class, 'showChat'])->name('chat');
Route::get('/chat/{recipient}', [AssistCustomerController::class, 'showChat'])->middleware('auth')->name('chat.recipient');
Route::post('/send-message', [ChatController::class, 'sendMessage'])->middleware('auth');
Route::get('/messages/{conversationId}', [ChatController::class, 'getMessages']);

// Testing routes
Route::get('/triggerevent', [LogController::class, 'triggerEvent']);
Route::get('/test-email', function () {
    Mail::raw('This is a test email from Symfony Mailer in Laravel 9.', function ($message) {
        $message->to('nyctuss@gmail.com')->subject('Test Email');
    });

    return 'Test email sent. Check your inbox.';
});

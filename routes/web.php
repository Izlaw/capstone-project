<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AddOrderController;
use App\Http\Controllers\UploadOrderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UploadOrderMaleController;
use App\Http\Controllers\CustomerSupportController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\UploadOrderFemaleController;
use App\Http\Controllers\ChatSupportController;
use App\Http\Controllers\ExampleController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

Route::get('/addorder', [AddOrderController::class, 'index'])->name('addorder');

Route::get('/uploadorder', [UploadOrderController::class, 'index'])->name('uploadorder');

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/uploadordermale', [UploadOrderMaleController::class, 'index'])->name('uploadordermale');

Route::get('/uploadorderfemale', [UploadOrderFemaleController::class, 'index'])->name('uploadorderfemale');

Route::get('/customersupport', [CustomerSupportController::class, 'index'])->name('customersupport');

Route::get('/chatsupport', [ChatSupportController::class, 'index'])->name('chatsupport');

Route::get('/example', [ExampleController::class, 'index'])->name('example');

Route::get('/employeedashboard', function () {
    return view('employeeui.empdboard');
})->middleware('auth')->name('employee.dashboard');

Route::get('/admindashboard', function () {
    return view('adminui.admindboard'); // Path to your admin dashboard view
})->middleware('auth')->name('admin.dashboard');

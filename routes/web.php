<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AddOrderController;
use App\Http\Controllers\UploadOrderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UploadOrderMaleController;
use App\Http\Controllers\CustomerSupportController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Login1Controller;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\UploadOrderFemaleController;

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

Route::get('/login', [LoginController::class, 'index'])->name('login');

Route::get('/login1', [Login1Controller::class, 'index'])->name('login1');


Route::get('/addorder', [AddOrderController::class, 'index'])->name('addorder');

Route::get('/uploadorder', [UploadOrderController::class, 'index'])->name('uploadorder');

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/uploadordermale', [UploadOrderMaleController::class, 'index'])->name('uploadordermale');

Route::get('/uploadorderfemale', [UploadOrderFemaleController::class, 'index'])->name('uploadorderfemale');

Route::get('/customersupport', [CustomerSupportController::class, 'index'])->name('customersupport');

Route::get('/chatsupport', [ChatSupportController::class, 'index'])->name('chatsupport');


Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);
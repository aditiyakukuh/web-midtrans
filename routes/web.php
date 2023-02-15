<?php

use App\Http\Controllers\PaymentCallbackController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-payment', [PaymentController::class, 'index'])->name('test.payment');
Route::post('/payment-callback', PaymentCallbackController::class)->name('payment_callback');

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PayPalController;

Route::get('/', function () {
    return view('welcome');

});

Route::get('/paypal/success', [PayPalController::class, 'executePayment'])->name('paypal.success');
Route::get('/paypal/cancel', [PayPalController::class, 'cancel'])->name('paypal.cancel');

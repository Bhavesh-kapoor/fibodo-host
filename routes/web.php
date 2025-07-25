<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

//Route::match(['get', 'post'], '/hostedpayment', [\App\Http\Controllers\FinanceController::class, 'capturePayment']);
Route::match(['get', 'post'], '/securecardregistration', [\App\Http\Controllers\FinanceController::class, 'captureSecureToken']);
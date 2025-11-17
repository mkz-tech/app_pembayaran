<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WebhookController;

Route::get('/', function () {return view('welcome');});

Route::get('/', [PaymentController::class, 'showForm']);
Route::post('/pay', [PaymentController::class, 'createPayment'])->name('pay');
Route::post('/midtrans/notification', [WebhookController::class, 'handle']); // webhook endpoint
route::get('/payment/checkout/{order_id}',[PaymentController::class,'checkout'])->name('payment.checkout');
Route::get('/payment/status/{order_id}', [PaymentController::class,'checkStatus']); //kembali ke tampilan pembayaran
Route::get('/pay/qris/{order_id}', [\App\Http\Controllers\PaymentController::class, 'qris']);


<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Order;

Route::get('/order/{order_id}', function($order_id){
    $order = Order::where('order_id', $order_id)->first();
    if(!$order) return response()->json(['error'=>'not found'],404);
    return response()->json([
        'order_id' => $order->order_id,
        'status' => $order->status,
        'jumlah' => $order->jumlah
    ]);
});

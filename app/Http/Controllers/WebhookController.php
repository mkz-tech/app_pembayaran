<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller {
    public function handle(Request $request) {
        $payload = $request->all();
        Log::info('Midtrans Notification:', $payload);

        $order_id = $payload['order_id'] ?? null;
        $status_code = $payload['status_code'] ?? '';
        $gross_amount = $payload['gross_amount'] ?? '';

        $server_key = config('midtrans.server_key');
        $calculated = hash('sha512', $order_id.$status_code.$gross_amount.$server_key);

        if(($payload['signature_key'] ?? '') !== $calculated) {
            Log::warning('Invalid Midtrans signature');
            return response('Invalid signature', 403);
        }

        $order = Order::where('order_id', $order_id)->first();
        if($order) {
            $order->status = $payload['transaction_status'] ?? $order->status;
            $order->tipe_pembayaran = $payload['payment_type'] ?? $order->tipe_pembayaran;
            $order->raw_response = json_encode($payload);
            $order->save();
            Log::info("Order {$order->order_id} updated: {$order->status}");

            // Trigger serial script in background (Arduino)
            $port = escapeshellarg(env('ARDUINO_PORT', 'COM3'));
            $cmd = "python3 ".escapeshellarg(base_path('scripts/send_serial.py'))." $port 1 > /tmp/serial_{$order->order_id}.log 2>&1 &";
            exec($cmd);
        }
        return response('OK', 200);
    }
}

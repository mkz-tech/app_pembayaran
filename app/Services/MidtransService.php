<?php

namespace App\Services;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService {
    public function __construct() {
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isSanitized = true;
        Config::$is3ds = true;
        Config::$isProduction = !config('midtrans.is_sandbox');
    }

    public function createSnapTransaction($orderId, $jumlah, $itemDetails = []) {
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $jumlah,
            ],
            'item_details' => $itemDetails,
        ];
        return Snap::createTransaction($params);
    }
}

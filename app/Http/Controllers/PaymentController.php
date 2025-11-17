<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Services\MidtransService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller {
    protected $midtrans;
    public function __construct(MidtransService $midtrans) {
        $this->midtrans = $midtrans;
    }

    public function showForm() {
        return view('payment.form');
    }

    public function createPayment(Request $req) {
        $jumlah = (int)$req->input('jumlah', 10000);
        $orderId = 'SMD-'.Str::upper(Str::random(8));
        $order = Order::create([
            'order_id' => $orderId,
            'nama_produk' => $req->input('nama_produk','E-Toll'),
            'jumlah' => $jumlah,
            'status' => 'pending',
        ]);

        $snap = $this->midtrans->createSnapTransaction($orderId, $jumlah, [
            ['id'=>'item1','price'=>$jumlah,'quantity'=>1,'name'=>$order->nama_produk]
        ]);

        return view('payment.checkout', [
            'snapToken' => $snap->token ?? null,
            'redirectUrl' => $snap->redirect_url ?? null,
            'order' => $order
        ]);
    }

    public function checkStatus($order_id) {
        $order = Order::where('order_id', $order_id)->first();
        if (!$order) {
            return response()->json(['status' => 'NOT_FOUND']) ;
        }

        return response()->json(['status'=> $order->status]) ;
    }

    public function pay(Request $req)
    {
        Log::info('User menekan tombol Bayar', [
            'jumlah' => $req->jumlah
        ]);
    }

    public function qris($order_id)
    {
        // Ambil data order dari database
        $order = Order::where('order_id', $order_id)->firstOrFail();

        // Parameter QRIS Midtrans
        $params = [
            'payment_type' => 'qris',
            'transaction_details' => [
                'order_id' => $order->order_id,
                'gross_amount' => (int)$order->jumlah, // ← harus gross_amount
            ],
        ];
        
        // Generate QRIS
        $qris = \Midtrans\CoreApi::charge($params);
        
        //tambah proteksi
        $qris_url = null;

        if(isset($qris->actions) && count($qris->actions) > 0) {
            foreach($qris->actions as $act) {
                if ($act->name === "generate-qr-code"){
                    $qris_url = $act->url;
                }
            }
        };

        // Kirim QR ke view
        return view('checkout', [
            'order' => $order,
            'qris_url' => $qris->actions[0]->url
        ]);
    }
}
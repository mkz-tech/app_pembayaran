<!doctype html>

<html>
    <head><meta charset="utf-8"><title>Checkout - app_pembayaran</title></head>
    <body>
        <h3>Total Bayar Rp. {{ number_format($order->jumlah,0,',','.') }}</h3>
        @if($snapToken)
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
            <button id="pay-button">BAYAR</button>
        <script>
            document.getElementById('pay-button').addEventListener('click', function(){
                snap.pay('{{ $snapToken }}', {
                    onSuccess: function(result){ console.log(result); window.location.href = "https://steadfastly-tuitionary-arletta.ngrok-free.dev"},
                    onPending: function(result){ console.log(result); alert('Payment pending'); },
                    onError: function(result){ console.log(result); alert('Payment error'); }
                });
            });
        </script>
      
    @else
        <a href="{{ $redirectUrl }}">Bayar (redirect)</a>
    @endif

    <p>Silahkan klik tombol bayar<br>untuk menampilkan metode pembayaran. </p>
    
    {{-- @if(isset($qris_url))
        <h3>Scan QRIS</h3>
        <img src="{{ $qris_url }}" width="250">
        @else
        <p>QRIS belum tersedia</p>
    @endif

    <p>Order_id: <strong>{{ $order->order_id }}</strong></p> --}}
    
    </body>
</html>

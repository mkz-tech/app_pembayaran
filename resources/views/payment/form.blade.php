<!doctype html>

<html>
    <head><meta charset="utf-8"><title>Form Pembayaran - app_pembayaran</title></head>
    <body>
        <h2>Pembayaran Portal Kampung</h2>
        <form method="post" action="/pay">
            @csrf
            <label>Harga tiket masuk Rp. 10.000,-</label><br>
            {{-- <input type="text" name="nama_produk" value="HP Samsung"><br><br> --}}
            {{-- <label>Jumlah (Rp.)</label><br> --}}
            {{-- <input type="number" name="jumlah" value="10000"><br><br> --}}
            <label>Klik tombol dibawah untuk pembayaran</label><br><br>
            <button type="submit">SELANJUTNYA</button>

            {{-- @if(request()->has('success'))
                <div style="padding:10px; background:#d4edda; color:#155724; margin-bottom:15px;">
                    Pembayaran berhasil! Silakan melakukan transaksi berikutnya.
                </div>
            @endif --}}
            
        </form>
    </body>
</html>

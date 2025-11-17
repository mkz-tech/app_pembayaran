<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class order extends Model
{
    use HasFactory;
    protected $fillable = ['order_id','nama_produk','jumlah','tipe_pembayaran','status','raw_response'];
    protected $casts = ['jumlah' => 'integer'];
}

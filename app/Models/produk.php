<?php

namespace App\Models;

use Http\Controllers\Models\pembelian;
use Http\Controllers\Models\penjualan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class produk extends Model
{
    use HasFactory;

    protected  $table = 'produks';

    protected $fillable = ['nama_produk', 'harga_beli', 'harga_jual', 'kategori', 'stok'];

    public function pembelian()
    {
        return $this->hasMany(pembelian::class,);
    }
    public function penjualan()
    {
        return $this->hasMany(penjualan::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class pembelian extends Model
{
    use HasFactory;

    protected  $table = 'pembelian';

    protected $fillable = ['produk_id', 'jumlah', 'total', 'tanggal', 'user_id'];

    public function produk()
    {
        return $this->belongsTo(produk::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

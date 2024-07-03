<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\penjualan;
use App\Models\produk;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Mengambil semua data user dan produk
        $user = User::all();
        $produk = produk::all();

        // Mengambil data produk dengan nama 'saldo'
        $saldoProduk = produk::where('nama_produk', 'saldo')->first();

        // Jika produk dengan nama 'saldo' ada, ambil stoknya, jika tidak set stok menjadi 0
        $saldoStok = $saldoProduk ? $saldoProduk->stok : 0;

        // Mengambil tanggal hari ini
        $today = Carbon::today();

        // Mengambil data penjualan
        $data = DB::table('penjualan')->get();

        // Menghitung total penjualan hari ini
        $totalSales = DB::table('penjualan')->whereDate('tanggal', $today)->count();

        // Menghitung total jumlah user
        $jumlahUser = $user->count();

        // Menghitung total jumlah produk
        $jumlahProduk = $produk->count();

        // Mengembalikan view 'dashboard' dengan data yang telah dihitung
        return view('dashboard', compact('saldoStok', 'totalSales', 'jumlahUser', 'jumlahProduk', 'data'));
    }
}

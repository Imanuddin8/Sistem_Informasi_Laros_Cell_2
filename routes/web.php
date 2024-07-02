<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\dashboard\Analytics;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CetakController;



// authentication
Route::controller(AuthController::class)->group(function () {
  Route::get('', 'login')->name('login');
  Route::post('login', 'loginAction')->name('login.action');
  Route::post('logout', 'logout')->middleware('auth')->name('logout');
});

Route::middleware('auth', 'role:admin,karyawan')->group(function(){
    // Main Page Route
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // user
    Route::get('/user', [UserController::class, 'index'])->name('user');
    Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
    Route::post('/user/store', [UserController::class, 'store'])->name('user.store');
    Route::get('/user/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
    Route::post('/user/{id}', [UserController::class, 'update'])->name('user.update');
    Route::get('/user/delete/{id}', [UserController::class, 'destroy'])->name('user.delete');

    // produk
    Route::get('/barang', [ProdukController::class, 'index'])->name('produk');
    Route::get('/produk/create', [ProdukController::class, 'create'])->name('produk.create');
    Route::POST('/produk/store', [ProdukController::class, 'store'])->name('produk.store');
    Route::get('/produk/edit/{id}', [ProdukController::class, 'edit'])->name('produk.edit');
    Route::POST('/produk/{id}', [ProdukController::class, 'update'])->name('produk.update');
    Route::get('/produk/delete/{id}', [ProdukController::class, 'destroy'])->name('produk.delete');

    // pembelian
    Route::get('/pembelian', [PembelianController::class, 'index'])->name('pembelian');
    Route::get('/pembelian/create', [PembelianController::class, 'create'])->name('pembelian.create');
    Route::post('/pembelian/store', [PembelianController::class, 'store'])->name('pembelian.store');
    Route::get('/pembelian/edit/{id}', [PembelianController::class, 'edit'])->name('pembelian.edit');
    Route::post('/pembelian/{id}', [PembelianController::class, 'update'])->name('pembelian.update');
    Route::get('/pembelian/delete/{id}', [PembelianController::class, 'destroy'])->name('pembelian.delete');
    Route::get('/pembelian/filter', [PembelianController::class, 'filter'])->name('pembelian.filter');
    Route::get('/pembelian/cetak', [PembelianController::class, 'cetak'])->name('pembelian.cetak');

    // penjualan
    Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan');
    Route::get('/penjualan/create', [PenjualanController::class, 'create'])->name('penjualan.create');
    Route::post('/penjualan/store', [PenjualanController::class, 'store'])->name('penjualan.store');
    Route::get('/penjualan/edit/{id}', [PenjualanController::class, 'edit'])->name('penjualan.edit');
    Route::post('/penjualan/{id}', [PenjualanController::class, 'update'])->name('penjualan.update');
    Route::get('/penjualan/delete/{id}', [PenjualanController::class, 'destroy'])->name('penjualan.delete');
    Route::get('/penjualan/filter', [PenjualanController::class, 'filter'])->name('penjualan.filter');
    Route::get('/penjualan/cetak', [PenjualanController::class, 'cetak'])->name('penjualan.cetak');
});

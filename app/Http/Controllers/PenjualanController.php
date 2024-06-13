<?php

namespace App\Http\Controllers;

use App\Models\penjualan;
use App\Models\produk;
use App\Models\User;
use App\Http\Requests\StorepenjualanRequest;
use App\Http\Requests\UpdatepenjualanRequest;
use Illuminate\Http\Request;
use PDF;

class PenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      $penjualan = penjualan::orderBy('created_at', 'desc')->get();
      $produk = produk::all();
      $user = User::all();
      return view('penjualan.penjualan', compact('penjualan', 'produk', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
      $penjualan = penjualan::all();
      $produk = produk::all();
      $user = User::all();
      return view('penjualan.create', compact('penjualan', 'produk', 'user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorepenjualanRequest $request)
    {
      $produk = produk::all();
      $user = User::all();
      $produk = Produk::find($request->produk_id);

      if ($produk->kategori == 'saldo') {
          $total = $request->jumlah + '2000';
      } else {
          $total = $request->jumlah * $produk->harga_jual;
      }

      $penjualan = penjualan::create([
        'produk_id' => $request->produk_id,
        'no' => $request->no,
        'jumlah' => $request->jumlah,
        'total' => $total,
        'tanggal' => $request->tanggal,
        'user_id' => $request->user_id
      ]);
      return redirect()->route('penjualan')->with('toast_success', 'Transaksi berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
      $penjualan = penjualan::findOrFail($id);
      $user = User::all();
      $produk = produk::all();
      return view('penjualan.update', compact('produk','penjualan', 'user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatepenjualanRequest $request, $id)
    {
      $penjualan = penjualan::findOrFail($id);
      $user = User::all();
      $produk = produk::all();
      $produk = Produk::find($request->produk_id);

      if ($produk->kategori == 'saldo') {
          $total = $request->jumlah + '2000';
      } else {
          $total = $request->jumlah * $produk->harga_jual;
      }

      $penjualan->update([
        'produk_id' => $request->produk_id,
        'no' => $request->no,
        'jumlah' => $request->jumlah,
        'total' => $total,
        'tanggal' => $request->tanggal,
        'user_id' => $request->user_id
      ]);
      return redirect()->route('penjualan')->with('toast_success', 'Transaksi berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
      $penjualan = penjualan::findOrFail($id);
      $penjualan->delete();

      return redirect()->route('penjualan')->with('toast_success', 'Transaksi berhasil dihapus.');
    }

    public function filter(Request $request)
    {
        $query = penjualan::query();

        // Filter by nama_produk
        if ($request->filled('nama_produk')) {
            $query->whereHas('produk', function($q) use ($request) {
                $q->where('nama_produk', 'like', '%' . $request->input('nama_produk') . '%');
            });
        }

        // Filter by tanggal_mulai
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal', '>=', $request->input('tanggal_mulai'));
        }

        // Filter by tanggal_akhir
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal', '<=', $request->input('tanggal_akhir'));
        }

        $penjualan = $query->get();

        return view('penjualan.penjualan', compact('penjualan'));
    }

    public function cetak(Request $request)
    {
        // Ambil data transaksi dari database
        $produk = produk::all();
        $user = User::all();

        $query = penjualan::query();

        // Filter by nama_produk
        if ($request->filled('nama_produk')) {
            $query->whereHas('produk', function($q) use ($request) {
                $q->where('nama_produk', 'like', '%' . $request->input('nama_produk') . '%');
            });
        }

        // Filter by tanggal_mulai
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal', '>=', $request->input('tanggal_mulai'));
        }

        // Filter by tanggal_akhir
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal', '<=', $request->input('tanggal_akhir'));
        }

        $penjualan = $query->get();

        $jumlahPenjualan = $penjualan->count();

        $jumlahTotal = $penjualan->sum('total');

        $pdf = PDF::loadView('penjualan.cetak', [
            'penjualan' => $penjualan,
            'user' => $user,
            'produk' => $produk,
            'jumlahTotal' => $jumlahTotal,
            'jumlahPenjualan' => $jumlahPenjualan,
            'tanggal_mulai' => $request->input('tanggal_mulai'),
            'tanggal_akhir' => $request->input('tanggal_akhir'),
            'nama_produk' => $request->input('nama_produk'),
        ]);

        return $pdf->download('laporan-transaksi-penjualan.pdf');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\pembelian;
use App\Models\produk;
use App\Models\User;
use App\Http\Requests\StorepembelianRequest;
use App\Http\Requests\UpdatepembelianRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

class PembelianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      $pembelian = pembelian::orderBy('created_at', 'desc')->get();
      $produk = produk::all();
      $user = User::all();
      return view('pembelian.pembelian', compact('pembelian', 'produk', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
      $pembelian = pembelian::all();
      $produk = produk::all();
      $user = User::all();
      return view('pembelian.create', compact('produk','pembelian', 'user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorepembelianRequest $request)
    {
      $produk = produk::all();
      $user = User::all();
      $produk = produk::all();

      // Hapus titik ribuan dari input jumlah
      $jumlah = str_replace('.', '', $request->jumlah);

      // Ambil produk berdasarkan produk_id dari request
      $selectedProduct = produk::find($request->produk_id);

      // Hitung total berdasarkan kategori produk
      if ($selectedProduct->kategori == 'saldo') {
          $total = $jumlah;
          // Tambah stok untuk semua produk dengan kategori 'saldo'
          produk::where('kategori', 'saldo')->increment('stok', $jumlah);
      } else {
          $total = $jumlah * $selectedProduct->harga_beli;
          // Tambah stok untuk produk yang bukan kategori 'saldo'
          $selectedProduct->increment('stok', $jumlah);
      }

      $pembelian = pembelian::create([
        'produk_id' => $request->produk_id,
        'jumlah' => $jumlah,
        'total' => $total,
        'tanggal' => $request->tanggal,
        'user_id' => Auth::id()
      ]);
      return redirect()->route('pembelian')->with('toast_success', 'Transaksi berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
      $pembelian = pembelian::findOrFail($id);
      $produk = produk::all();
      $user = User::all();
      return view('pembelian.update', compact('produk','pembelian', 'user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatepembelianRequest $request, $id)
    {
        $pembelian = pembelian::findOrFail($id);
        $user = User::all();
        $produk = produk::all();

        // Hapus titik ribuan dari input jumlah
        $jumlah = str_replace('.', '', $request->jumlah);

        // Hapus titik ribuan dari input jumlah
        $jumlah = str_replace('.', '', $request->jumlah);

        // Ambil produk berdasarkan produk_id dari request
        $selectedProduct = produk::find($request->produk_id);

        // Hitung total berdasarkan kategori produk
        if ($selectedProduct->kategori == 'saldo') {
            $total = $jumlah;
            // Kembalikan stok untuk semua produk dengan kategori 'saldo'
            produk::where('kategori', 'saldo')->decrement('stok', $pembelian->jumlah); // Kembalikan stok sebelumnya
            produk::where('kategori', 'saldo')->increment('stok', $jumlah); // Tambah stok baru
        } else {
            $total = $jumlah * $selectedProduct->harga_beli;
            // Kembalikan stok untuk produk yang bukan kategori 'saldo'
            $selectedProduct->decrement('stok', $pembelian->jumlah); // Kembalikan stok sebelumnya
            $selectedProduct->increment('stok', $jumlah); // Tambah stok baru
        }

        $pembelian->update([
            'produk_id' => $request->produk_id,
            'jumlah' => $jumlah,
            'total' => $total,
            'tanggal' => $request->tanggal,
            'user_id' => Auth::id()
        ]);

        return redirect()->route('pembelian')
            ->with('toast_success', 'Transaksi berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $pembelian = pembelian::findOrFail($id);
        $pembelian->delete();

        // Ambil produk yang terlibat dalam pembelian ini
        $selectedProduct = produk::find($pembelian->produk_id);

        // Tambah stok berdasarkan kategori produk pada transaksi yang dihapus
        if ($selectedProduct->kategori == 'saldo') {
            // Tambah stok untuk semua produk dengan kategori 'saldo'
            produk::where('kategori', 'saldo')->decrement('stok', $pembelian->jumlah);
        } else {
            // Tambah stok untuk produk yang bukan kategori 'saldo'
            $selectedProduct->decrement('stok', $pembelian->jumlah);
        }

        return redirect()->route('pembelian')->with('toast_success', 'Transaksi berhasil dihapus');
    }

    public function filter(Request $request)
    {
        $query = pembelian::query();

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

        $pembelian = $query->get();

        return view('pembelian.pembelian', compact('pembelian'));
    }

    public function cetak(Request $request)
    {
        // Ambil data transaksi dari database
        $produk = produk::all();
        $user = User::all();

        $query = pembelian::query();

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

        $pembelian = $query->get();

        $jumlahPembelian = $pembelian->count();

        $jumlahTotal = $pembelian->sum('total');

        $tanggal_mulai = $request->input('tanggal_mulai');

        $tanggal_akhir = $request->input('tanggal_akhir');

        $nama_produk = $request->input('nama_produk');

        return view('pembelian.cetak', compact('pembelian', 'user', 'produk', 'jumlahPembelian', 'jumlahTotal', 'tanggal_mulai', 'tanggal_akhir', 'nama_produk'));
    }
}

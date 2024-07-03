<?php

namespace App\Http\Controllers;

use App\Models\penjualan;
use App\Models\produk;
use App\Models\User;
use App\Http\Requests\StorepenjualanRequest;
use App\Http\Requests\UpdatepenjualanRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

class PenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengambil semua data penjualan dan mengurutkannya berdasarkan waktu pembuatan secara descending (terbaru ke terlama)
        $penjualan = penjualan::orderBy('created_at', 'desc')->get();

        // Mengambil semua data produk dan user dari database
        $produk = produk::all();
        $user = User::all();

        // Mengembalikan view 'penjualan.penjualan' dengan data penjualan, produk, dan user yang telah diambil
        return view('penjualan.penjualan', compact('penjualan', 'produk', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Mengambil semua data penjualan, produk, dan user dari database
        $penjualan = penjualan::all();
        $produk = produk::all();
        $user = User::all();

        // Mengembalikan view 'penjualan.create' dengan data produk, penjualan, dan user yang telah diambil
        return view('penjualan.create', compact('penjualan', 'produk', 'user'));
    }
    
    public function store(StorepenjualanRequest $request)
    {
        // Mengambil semua data produk dan user dari database
        $user = User::all();
        $produk = produk::all();

        // Hapus titik ribuan dari input jumlah
        $jumlah = str_replace('.', '', $request->jumlah);

        // Ambil produk berdasarkan produk_id dari request
        $selectedProduct = produk::find($request->produk_id);

        // Cek stok produk sebelum melakukan penjualan
        if ($selectedProduct->stok < $jumlah) {
            return redirect()->back()->with('toast_error', 'Stok produk tidak mencukupi untuk transaksi ini')->withInput();
        }

         // Hitung total berdasarkan kategori produk
        if ($selectedProduct->kategori == 'saldo') {
            $total = $jumlah + 2000;
            // Kurangi stok untuk semua produk dengan kategori 'saldo'
            produk::where('kategori', 'saldo')->decrement('stok', $jumlah);
        } else {
            $total = $jumlah * $selectedProduct->harga_jual;
            // Kurangi stok untuk produk yang bukan kategori 'saldo'
            $selectedProduct->decrement('stok', $jumlah);
        }

        // Simpan perubahan stok produk
        $selectedProduct->save();

        $penjualan = penjualan::create([
            'produk_id' => $request->produk_id, // Menyimpan produk_id yang dikirim melalui request
            'no' => $request->no,               // Menyimpan no penjualan yang dikirim melalui request
            'jumlah' => $jumlah,                // Menyimpan jumlah penjualan yang telah diproses sebelumnya
            'total' => $total,                  // Menyimpan total harga penjualan yang telah dihitung sebelumnya
            'tanggal' => $request->tanggal,     // Menyimpan tanggal penjualan yang dikirim melalui request
            'user_id' => Auth::id()             // Menyimpan user_id dari pengguna yang sedang login
        ]);

        // Mengarahkan kembali ke route 'penjualan' dengan pesan sukses
        return redirect()->route('penjualan')->with('toast_success', 'Transaksi berhasil ditambahkan');
    }

    public function edit($id)
    {
        // Mengambil data penjualan berdasarkan ID yang diberikan.
        $penjualan = penjualan::findOrFail($id);

        // Mengambil semua data produk dan user dari database.
        $user = User::all();
        $produk = produk::all();

        // Mengembalikan view 'penjualan.update' dengan variabel 'produk', 'penjualan', dan 'user'.
        return view('penjualan.update', compact('produk','penjualan', 'user'));
    }

    public function update(UpdatepenjualanRequest $request, $id)
    {
        // Mengambil data penjualan berdasarkan ID yang diberikan.
        $penjualan = penjualan::findOrFail($id);

        // Mengambil semua data produk dan user dari database.
        $user = User::all();
        $produk = produk::all();

        // Hapus titik ribuan dari input jumlah
        $jumlah = str_replace('.', '', $request->jumlah);

        // Ambil produk berdasarkan produk_id dari request
        $selectedProduct = produk::find($request->produk_id);

        // Cek stok produk sebelum melakukan penjualan
        if ($selectedProduct->stok < $jumlah) {
            return redirect()->back()->with('toast_error', 'Stok produk tidak mencukupi untuk transaksi ini')->withInput();
        }

         // Hitung total berdasarkan kategori produk
        if ($selectedProduct->kategori == 'saldo') {
            $total = $jumlah + 2000;
            // Kurangi stok untuk semua produk dengan kategori 'saldo'
            produk::where('kategori', 'saldo')->increment('stok', $penjualan->jumlah); // Kembalikan stok sebelumnya
            produk::where('kategori', 'saldo')->decrement('stok', $jumlah); // Kurangi stok baru
        } else {
            $total = $jumlah * $selectedProduct->harga_jual;
            // Kurangi stok untuk produk yang bukan kategori 'saldo'
            $selectedProduct->increment('stok', $penjualan->jumlah); // Kembalikan stok sebelumnya
            $selectedProduct->decrement('stok', $jumlah); // Kurangi stok baru
        }

        // Simpan perubahan stok produk
        $selectedProduct->save();

        // Mengupdate data penjualan di database dengan menggunakan model penjualan
        $penjualan->update([
            'produk_id' => $request->produk_id, // Menyimpan produk_id yang dikirim melalui request
            'no' => $request->no,               // Menyimpan no penjualan yang dikirim melalui request
            'jumlah' => $jumlah,                // Menyimpan jumlah penjualan yang telah diproses sebelumnya
            'total' => $total,                  // Menyimpan total harga penjualan yang telah dihitung sebelumnya
            'tanggal' => $request->tanggal,     // Menyimpan tanggal penjualan yang dikirim melalui request
            'user_id' => Auth::id()             // Menyimpan user_id dari pengguna yang sedang login
        ]);

        // Mengarahkan kembali ke route 'penjualan' dengan pesan sukses
        return redirect()->route('penjualan')->with('toast_success', 'Transaksi berhasil diperbarui');
    }

    public function destroy($id)
    {
        // Mengambil data penjualan berdasarkan ID yang diberikan.
        $penjualan = penjualan::findOrFail($id);

        // Menghapus data penjualan yang ditemukan dari database.
        $penjualan->delete();

        // Ambil produk yang terlibat dalam penjualan ini
        $selectedProduct = produk::find($penjualan->produk_id);

        // Kembalikan stok berdasarkan kategori produk pada transaksi yang dihapus
        if ($selectedProduct->kategori == 'saldo') {
            // Kembalikan stok untuk semua produk dengan kategori 'saldo'
            produk::where('kategori', 'saldo')->increment('stok', $penjualan->jumlah);
        } else {
            // Kembalikan stok untuk produk yang bukan kategori 'saldo'
            $selectedProduct->increment('stok', $penjualan->jumlah);
        }

        // Mengarahkan ke route 'penjualan' dengan pesan sukses
        return redirect()->route('penjualan')->with('toast_success', 'Transaksi berhasil dihapus.');
    }

    public function filter(Request $request)
    {
        // Membuat instance query builder dari model `penjualan`.
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

        // Menjalankan query yang telah dibangun menggunakan query builder dan mengambil semua hasilnya.
        $penjualan = $query->get();

        // Mengarahkan ke route 'penjualan'
        return view('penjualan.penjualan', compact('penjualan'));
    }

    public function cetak(Request $request)
    {
        // Ambil data transaksi dari database
        $produk = produk::all();
        $user = User::all();

        // Membuat instance query builder dari model `penjualan`.
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

        // Menjalankan query yang telah dibangun menggunakan query builder dan mengambil semua hasilnya.
        $penjualan = $query->get();

        // Menghitung jumlah transaksi penjualan
        $jumlahPenjualan = $penjualan->count();

        // Menghitung jumlah total
        $jumlahTotal = $penjualan->sum('total');

        // Input tanggal mulai
        $tanggal_mulai = $request->input('tanggal_mulai');

        // Input tanggal akhir
        $tanggal_akhir = $request->input('tanggal_akhir');

        // Iput nama produk
        $nama_produk = $request->input('nama_produk');

        // Mengarahkan ke route 'penjualan.cetak' untuk di cetak
        return view('penjualan.cetak', compact('penjualan', 'user', 'produk', 'jumlahPenjualan', 'jumlahTotal', 'tanggal_mulai', 'tanggal_akhir', 'nama_produk'));
    }
}

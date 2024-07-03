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
    public function index()
    {
        // Mengambil semua data pembelian dan mengurutkannya berdasarkan waktu pembuatan secara descending (terbaru ke terlama)
        $pembelian = pembelian::orderBy('created_at', 'desc')->get();

        // Mengambil semua data produk dan user dari database
        $produk = produk::all();
        $user = User::all();

        // Mengembalikan view 'pembelian.pembelian' dengan data pembelian, produk, dan user yang telah diambil
        return view('pembelian.pembelian', compact('pembelian', 'produk', 'user'));
    }

    public function create()
    {
        // Mengambil semua data pembelian, produk, dan user dari database
        $pembelian = pembelian::all();
        $produk = produk::all();
        $user = User::all();

        // Mengembalikan view 'pembelian.create' dengan data produk, pembelian, dan user yang telah diambil
        return view('pembelian.create', compact('produk', 'pembelian', 'user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorepembelianRequest $request)
    {
        // Mengambil semua data produk dan user dari database
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

        // Simpan perubahan stok produk
        $selectedProduct->save();

        // Membuat data pembelian baru di database dengan menggunakan model pembelian
        $pembelian = pembelian::create([
            'produk_id' => $request->produk_id,  // Menyimpan produk_id yang dikirim melalui request
            'jumlah' => $jumlah,                // Menyimpan jumlah pembelian yang telah diproses sebelumnya
            'total' => $total,                  // Menyimpan total harga pembelian yang telah dihitung sebelumnya
            'tanggal' => $request->tanggal,     // Menyimpan tanggal pembelian yang dikirim melalui request
            'user_id' => Auth::id()             // Menyimpan user_id dari pengguna yang sedang login
        ]);

        // Mengarahkan kembali ke route 'pembelian' dengan pesan sukses
        return redirect()->route('pembelian')->with('toast_success', 'Transaksi berhasil ditambahkan');
    }

    public function edit($id)
    {
        // Mengambil data pembelian berdasarkan ID yang diberikan.
        $pembelian = pembelian::findOrFail($id);

        // Mengambil semua data produk dan user dari database.
        $produk = produk::all();
        $user = User::all();

        // Mengembalikan view 'pembelian.update' dengan variabel 'produk', 'pembelian', dan 'user'.
        // Variabel-variabel ini akan tersedia di view dan dapat digunakan untuk menampilkan data yang diperlukan.
        return view('pembelian.update', compact('produk', 'pembelian', 'user'));
    }

    public function update(UpdatepembelianRequest $request, $id)
    {
        // Mengambil data pembelian berdasarkan ID yang diberikan.
        $pembelian = pembelian::findOrFail($id);

        // Mengambil semua data produk dan user dari database.
        $produk = produk::all();
        $user = User::all();

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

        // Simpan perubahan stok produk
        $selectedProduct->save();

        // Mengupdate data pembelian di database dengan menggunakan model pembelian
        $pembelian->update([
            'produk_id' => $request->produk_id,  // Menyimpan produk_id yang dikirim melalui request
            'jumlah' => $jumlah,                // Menyimpan jumlah pembelian yang telah diproses sebelumnya
            'total' => $total,                  // Menyimpan total harga pembelian yang telah dihitung sebelumnya
            'tanggal' => $request->tanggal,     // Menyimpan tanggal pembelian yang dikirim melalui request
            'user_id' => Auth::id()             // Menyimpan user_id dari pengguna yang sedang login
        ]);

        // Mengarahkan kembali ke route 'pembelian' dengan pesan sukses
        return redirect()->route('pembelian')->with('toast_success', 'Transaksi berhasil diperbarui');
    }
    
    public function destroy($id)
    {
        // Mengambil data pembelian berdasarkan ID yang diberikan.
        $pembelian = pembelian::findOrFail($id);

        // Menghapus data pembelian yang ditemukan dari database.
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

        // Mengarahkan ke route 'pembelian' dengan pesan sukses
        return redirect()->route('pembelian')->with('toast_success', 'Transaksi berhasil dihapus');
    }

    public function filter(Request $request)
    {
        // Membuat instance query builder dari model `pembelian`.
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

        // Menjalankan query yang telah dibangun menggunakan query builder dan mengambil semua hasilnya.
        $pembelian = $query->get();

        // Mengarahkan ke route 'pembelian'
        return view('pembelian.pembelian', compact('pembelian'));
    }

    public function cetak(Request $request)
    {
        // Ambil data produk dan user dari database
        $produk = produk::all();
        $user = User::all();

        // Membuat instance query builder dari model `pembelian`.
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

        // Menjalankan query yang telah dibangun menggunakan query builder dan mengambil semua hasilnya.
        $pembelian = $query->get();

        // Menghitung jumlah transaksi pembelian
        $jumlahPembelian = $pembelian->count();

        // Menghitung jumlah total
        $jumlahTotal = $pembelian->sum('total');

        // Input tanggal mulai
        $tanggal_mulai = $request->input('tanggal_mulai');

        // Input tanggal akhir
        $tanggal_akhir = $request->input('tanggal_akhir');

        // Iput nama produk
        $nama_produk = $request->input('nama_produk');

        // Mengarahkan ke route 'pembelian.cetak' untuk di cetak
        return view('pembelian.cetak', compact('pembelian', 'user', 'produk', 'jumlahPembelian', 'jumlahTotal', 'tanggal_mulai', 'tanggal_akhir', 'nama_produk'));
    }
}

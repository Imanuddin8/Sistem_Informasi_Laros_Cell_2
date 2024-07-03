<?php

namespace App\Http\Controllers;

use App\Models\produk;
use App\Http\Requests\StoreprodukRequest;
use App\Http\Requests\UpdateprodukRequest;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengambil semua data produk dan mengurutkannya berdasarkan waktu pembuatan secara descending (terbaru ke terlama)
        $produk = produk::orderBy('created_at', 'desc')->get();

        // Mengembalikan view 'produk.produk' dengan data produk yang telah diambil
        return view('produk.produk', compact('produk'));
    }

    public function create()
    {
        // Mengambil semua data produk dari database
        $produk = produk::all();

        // Mengembalikan view 'produk.create' dengan data produk yang telah diambil
        return view('produk.create', compact('produk'));
    }

    public function store(StoreprodukRequest $request)
    {
        // Hapus titik ribuan dari input jumlah
        $harga_beli = str_replace('.', '', $request->harga_beli);
        $harga_jual = str_replace('.', '', $request->harga_jual);

        // Menentukan nilai stok berdasarkan kategori produk.
        $stok = $request->kategori === 'saldo' ? Produk::where('nama_produk', 'saldo')->value('stok') : 0;

        // Membuat entri baru dalam tabel `produk` dengan data yang diberikan dari request.
        $produk = produk::create([
            'nama_produk' => $request->nama_produk, // Mengisi kolom 'nama_produk' dengan nilai dari request.
            'kategori' => $request->kategori,       // Mengisi kolom 'kategori' dengan nilai dari request.
            'harga_beli' => $harga_beli,            // Mengisi kolom 'harga_beli' dengan nilai variabel $harga_beli.
            'harga_jual' => $harga_jual,            // Mengisi kolom 'harga_jual' dengan nilai variabel $harga_jual.
            'stok' => $stok                        // Mengisi kolom 'stok' dengan nilai yang ditentukan sebelumnya.
        ]);

        // Mengarahkan kembali ke route 'produk' dengan pesan sukses
        return redirect()->route('produk')->with('toast_success', 'Produk berhasil ditambahkan');
    }

    public function edit($id)
    {
        // Mengambil data produk berdasarkan ID yang diberikan.
        $produk = produk::findOrFail($id);

        // Mengembalikan view 'produk.update' dengan data produk yang telah diambil
        return view('produk.update', compact('produk'));
    }
    
    public function update(UpdateprodukRequest $request, $id)
    {
        // Mengambil data produk berdasarkan ID yang diberikan.
        $produk = produk::findOrFail($id);

        // Hapus titik ribuan dari input jumlah
        $harga_beli = str_replace('.', '', $request->harga_beli);
        $harga_jual = str_replace('.', '', $request->harga_jual);

        // Periksa kondisi kategori
        if ($produk->kategori == 'saldo' && $request->kategori != 'saldo') {
            // Jika kategori berubah dari 'saldo' menjadi kategori lain
            $produk->stok = 0;
        } elseif ($produk->kategori != 'saldo' && $request->kategori == 'saldo') {
            // Jika kategori berubah menjadi 'saldo', stok diupdate dari produk dengan nama 'saldo'
            $saldoProduct = produk::where('nama_produk', 'saldo')->first();
            if ($saldoProduct) {
                $produk->stok = $saldoProduct->stok;
            }
        }

        // Update data produk
        $produk->update([
            'nama_produk' => $request->nama_produk, // Mengisi kolom 'nama_produk' dengan nilai dari request.
            'kategori' => $request->kategori,       // Mengisi kolom 'kategori' dengan nilai dari request.
            'harga_beli' => $harga_beli,            // Mengisi kolom 'harga_beli' dengan nilai variabel $harga_beli.
            'harga_jual' => $harga_jual,            // Mengisi kolom 'harga_jual' dengan nilai variabel $harga_jual.
            'stok' => $produk->stok                 // stok tetap sama jika kategori tidak berubah
        ]);

        return redirect()->route('produk')->with('toast_success', 'Produk berhasil diperbarui');
    }

    public function destroy($id)
    {
        // Mengambil data produk berdasarkan ID yang diberikan.
        $produk = produk::findOrFail($id);

        // Menghapus data produk yang ditemukan dari database.
        $produk->delete();

        // Mengarahkan ke route 'produk' dengan pesan sukses
        return redirect()->route('produk')->with('toast_success', 'Produk berhasil dihapus.');
    }
}

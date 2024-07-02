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
        $produk = produk::orderBy('created_at', 'desc')->get();
        return view('produk.produk', compact('produk'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
      $produk = produk::all();
      return view('produk.create', compact('produk'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreprodukRequest $request)
    {
        // Hapus titik ribuan dari input jumlah
        $harga_beli = str_replace('.', '', $request->harga_beli);
        $harga_jual = str_replace('.', '', $request->harga_jual);

        $stok = $request->kategori === 'saldo' ? Produk::where('nama_produk', 'saldo')->value('stok') : 0;

        $produk = produk::create([
            'nama_produk' => $request->nama_produk,
            'kategori' => $request->kategori,
            'harga_beli' => $harga_beli,
            'harga_jual' => $harga_jual,
            'stok' => $stok,
        ]);

        return redirect()->route('produk')
            ->with('toast_success', 'Produk berhasil ditambahkan');
        }

    /**
     * Display the specified resource.
     */


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
      $produk = produk::findOrFail($id);
      return view('produk.update', compact('produk'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateprodukRequest $request, $id)
    {
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
            'nama_produk' => $request->nama_produk,
            'kategori' => $request->kategori,
            'harga_beli' => $harga_beli,
            'harga_jual' => $harga_jual,
            'stok' => $produk->stok // stok tetap sama jika kategori tidak berubah
        ]);

        return redirect()->route('produk')
            ->with('toast_success', 'Produk berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $produk = produk::findOrFail($id);
        $produk->delete();

        return redirect()->route('produk')->with('toast_success', 'Produk berhasil dihapus.');
    }
}

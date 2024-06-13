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
      $request->validate([
        'nama_produk' => 'required|string|max:255',
        'harga_beli' => 'required|string|max:255',
        'harga_jual' => 'required|string|max:255',
        'kategori' => 'required|string|max:255',
        'stok' => 'required|numeric',
      ]);

      produk::create($request->all());

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
      $produk->update($request->all());

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

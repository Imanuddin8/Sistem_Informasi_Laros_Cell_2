<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreuserRequest;
use App\Http\Requests\UpdateuserRequest;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
      if(auth()->user()->role == "admin"){
        // Mengambil semua data user dan mengurutkannya berdasarkan waktu pembuatan secara descending (terbaru ke terlama)
        $user = User::orderBy('created_at', 'desc')->get();

        // Mengembalikan view 'user.user' dengan data user yang telah diambil
        return view('user.user', compact('user'));
      }
      return abort(403, 'Unauthorized Page');
    }

    public function create()
    {
      if(auth()->user()->role == "admin"){
        // Mengambil semua data user dari database
        $user = User::all();

        // Mengembalikan view 'user.create' dengan data user yang telah diambil
        return view('user.create', compact('user'));
      }
      return abort(403, 'Unauthorized Page');
    }

    public function store(StoreuserRequest $request)
    {
        if(auth()->user()->role == "admin"){
            // Membuat entri baru dalam tabel `users` menggunakan data yang diterima dari request.
            $request = User::create([
                'nama' => $request->nama,                   // Mengisi kolom 'nama' dengan nilai dari request.
                'username' => $request->username,           // Mengisi kolom 'username' dengan nilai dari request.
                'password' => bcrypt($request->password),   // Mengisi kolom 'password' dengan nilai dari request yang telah dienkripsi menggunakan bcrypt.
                'role' => $request->role                    // Mengisi kolom 'role' dengan nilai dari request.
            ]);

            // Mengarahkan pengguna ke route 'user' dengan pesan sukses menggunakan flash message.
            return redirect()->route('user')->with('toast_success', 'User berhasil ditambahkan');
        }
        return abort(403, 'Unauthorized Page');
    }

    /**
     * Display the specified resource.
     */


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
      if(auth()->user()->role == "admin"){
        // Mengambil data user berdasarkan ID yang diberikan.
        $user = User::findOrFail($id);

        // Mengembalikan view 'user.update' dengan data user yang telah diambil
        return view('user.update', compact('user'));
      }
      return abort(403, 'Unauthorized Page');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateuserRequest $request, $id)
    {
        if(auth()->user()->role == "admin"){
            // Mengambil data user berdasarkan ID yang diberikan.
            $user = User::findOrFail($id);

            // Data yang akan diupdate
            $updateData = [
                'nama' => $request->nama,          // Mengisi kolom 'nama' dengan nilai dari request.
                'username' => $request->username,  // Mengisi kolom 'username' dengan nilai dari request.
                'role' => $request->role           // Mengisi kolom 'role' dengan nilai dari request.
            ];

            // Jika password diisi, tambahkan ke dalam data yang akan diupdate
            if ($request->filled('password')) {
                $updateData['password'] = bcrypt($request->password);
            }

            // Update user
            User::where('id', $id)->update($updateData);

            // Mengarahkan ke route 'user' dengan pesan sukses
            return redirect()->route('user')->with('toast_success', 'user berhasil diperbarui');
        }
        return abort(403, 'Unauthorized Page');
    }

    public function destroy($id)
    {
        if(auth()->user()->role == "admin"){
            // Mengambil data user berdasarkan ID yang diberikan.
            $user = User::findOrFail($id);

            // Menghapus data user yang ditemukan dari database.
            $user->delete();

            // Mengarahkan ke route 'user' dengan pesan sukses
            return redirect()->route('user')->with('toast_success', 'User berhasil dihapus.');
        }
        return abort(403, 'Unauthorized Page');
    }
}

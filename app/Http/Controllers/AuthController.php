<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    // Method untuk menampilkan halaman login
    public function login()
    {
        return view('auth.login'); // Mengembalikan view 'auth.login'
    }

    // Method untuk menangani aksi login
    public function loginAction(Request $request)
    {
        // Memeriksa kredensial pengguna (username dan password) yang diberikan
        if (Auth::attempt($request->only('username', 'password'))) {
            // Jika autentikasi berhasil, redirect ke halaman dashboard
            return redirect('/dashboard');
        }
        // Jika autentikasi gagal, redirect kembali ke halaman utama dengan pesan error
        return redirect('/')->with('error', 'Username atau password yang anda masukkan salah');
    }
    
    // Method untuk menangani aksi logout
    public function logout(Request $request)
    {
        // Mengeluarkan pengguna yang sedang login
        Auth::guard('web')->logout();

        // Menginvalidate sesi pengguna
        $request->session()->invalidate();

        // Redirect ke halaman utama
        return redirect('/');
    }
}

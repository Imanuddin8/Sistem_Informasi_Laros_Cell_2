<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
  public function login()
  {
      return view('auth.login');
  }

  public function loginAction(Request $request)
  {
      if (Auth::attempt($request->only('username', 'password'))) {
          return redirect('/dashboard');
      }
      return redirect('/')->with('error', 'Username atau password yang anda masukkan salah');
  }

  public function logout(Request $request)
  {
      Auth::guard('web')->logout();

      $request->session()->invalidate();

      return redirect('/');
  }
}

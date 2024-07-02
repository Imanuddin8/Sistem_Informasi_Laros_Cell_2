<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreuserRequest;
use App\Http\Requests\UpdateuserRequest;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      if(auth()->user()->role == "admin"){
        $user = User::orderBy('created_at', 'desc')->get();
        return view('user.user', compact('user'));
      }
      return abort(403, 'Unauthorized Page');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
      if(auth()->user()->role == "admin"){
        $user = User::all();
        return view('user.create', compact('user'));
      }
      return abort(403, 'Unauthorized Page');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreuserRequest $request)
    {
        if(auth()->user()->role == "admin"){
            $request = User::create([
                'nama' => $request->nama,
                'username' => $request->username,
                'password' => bcrypt($request->password),
                'role' => $request->role,
            ]);
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
        $user = User::findOrFail($id);
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
            $user = User::findOrFail($id);
            // Data yang akan diupdate
            $updateData = [
                'nama' => $request->nama,
                'username' => $request->username,
                'role' => $request->role,
            ];

            // Jika password diisi, tambahkan ke dalam data yang akan diupdate
            if ($request->filled('password')) {
                $updateData['password'] = bcrypt($request->password);
            }

            // Update user
            User::where('id', $id)->update($updateData);

            return redirect()->route('user')
                ->with('toast_success', 'user berhasil diperbarui');
        }
        return abort(403, 'Unauthorized Page');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if(auth()->user()->role == "admin"){
            $user = User::findOrFail($id);
            $user->delete();
            return redirect()->route('user')->with('toast_success', 'User berhasil dihapus.');
        }
        return abort(403, 'Unauthorized Page');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $data = User::latest()->get();

        return view('admin.users', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return redirect()->back()->with('success','Administrator berhasil ditambahkan.');
    }

    public function update(Request $request,$id)
    {
        $request->validate([
            'name'=>'required|max:255',
            'email'=>'required|email|unique:users,email,'.$id,
        ]);

        $user = User::findOrFail($id);

        $user->name = $request->name;
        $user->email = $request->email;

        if($request->password != ""){
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success','Administrator berhasil diperbarui.');
    }

    public function delete($id)
    {
        User::findOrFail($id)->delete();

        return redirect()->back()->with('success','Administrator berhasil dihapus.');
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Edukasi;

class EdukasiController extends Controller
{
    public function index()
    {
        $data = Edukasi::latest()->get();

        return view('admin.edukasi', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'link' => 'nullable'
        ]);

        Edukasi::create([
            'judul' => $request->judul,
            'link' => $request->link
        ]);

        return redirect()->back()->with('success', 'Edukasi berhasil ditambahkan!');
    }

    public function delete($id)
    {
        Edukasi::find($id)?->delete();

        return redirect()->back()->with('success', 'Edukasi berhasil dihapus!');
    }

    public function api()
    {
        return Edukasi::latest()->get();
    }
}
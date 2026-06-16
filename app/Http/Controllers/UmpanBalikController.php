<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UmpanBalik;

class UmpanBalikController extends Controller
{
    public function index()
    {
        $data = UmpanBalik::latest()->get();

        return view('admin.umpan_balik', compact('data'));
    }

    public function storeApi(Request $request)
    {
        $request->validate([
            'pesan' => 'required'
        ]);

        UmpanBalik::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'kategori' => $request->kategori,
            'pesan' => $request->pesan,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Umpan balik berhasil dikirim.'
        ]);
    }

    public function delete($id)
    {
        UmpanBalik::find($id)?->delete();

        return redirect()->back()->with('success', 'Umpan balik berhasil dihapus!');
    }
}
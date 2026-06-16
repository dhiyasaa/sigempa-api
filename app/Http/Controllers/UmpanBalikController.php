<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
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
        try {
            $request->validate([
                'nama' => 'nullable|string|max:255',
                'email' => 'nullable|string|max:255',
                'kategori' => 'nullable|string|max:255',
                'pesan' => 'required|string',
            ]);

            $insertData = [
                'nama' => $request->input('nama'),
                'email' => $request->input('email'),
                'kategori' => $request->input('kategori'),
                'pesan' => $request->input('pesan'),
            ];

            if (Schema::hasColumn('umpan_baliks', 'created_at')) {
                $insertData['created_at'] = now();
            }

            if (Schema::hasColumn('umpan_baliks', 'updated_at')) {
                $insertData['updated_at'] = now();
            }

            DB::table('umpan_baliks')->insert($insertData);

            return response()->json([
                'success' => true,
                'message' => 'Umpan balik berhasil dikirim.'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim umpan balik.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function delete($id)
    {
        UmpanBalik::find($id)?->delete();

        return redirect()->back()->with('success', 'Umpan balik berhasil dihapus!');
    }
}
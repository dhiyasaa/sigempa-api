<?php

namespace App\Http\Controllers;

use App\Models\Gempa;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class AdminController extends Controller
{
    public function dashboard() {
        $total = Gempa::count();
        $latest = Gempa::latest()->take(5)->get();
        return view('admin.dashboard', compact('total','latest'));
    }

    public function gempa() {
        $data = Gempa::latest()->paginate(10);
        return view('admin.gempa', compact('data'));
    }

    // =========================
    // 🔥 DEC LIST
    // =========================
    public function decList() {
        $data = Gempa::latest()->paginate(10);
        return view('admin.dec_list', compact('data'));
    }

    // =========================
    // 🔥 DEC DETAIL (STEP BY STEP)
    // =========================
    public function decDetail($id)
{
    $g = Gempa::findOrFail($id);

    $depth = (float) preg_replace('/[^0-9.]/', '', $g->kedalaman);

    // ===========================
    // Kirim ke FastAPI
    // ===========================
    $response = Http::post(
    env('DEC_API_URL') . '/predict',
    [
        'magnitudo' => (float) $g->magnitudo,
        'kedalaman' => $depth
    ]
);

    if (!$response->successful()) {
        abort(500, 'Gagal terhubung ke DEC API');
    }

    $dec = $response->json();

    return view('admin.dec_detail', [

        'g' => $g,

        'input' => $dec['input'],

        'normalized' => $dec['normalized'],

        'latent' => $dec['latent'],

        'centroids' => $dec['centroids'],

        'probability' => $dec['probability'],

        'cluster' => $dec['cluster'],

        'label' => $dec['label'],

        'status' => $dec['status'],

        'color' => $dec['color']

    ]);
}
}
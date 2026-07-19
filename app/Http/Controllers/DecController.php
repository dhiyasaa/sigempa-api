<?php

namespace App\Http\Controllers;

use App\Models\Gempa;
use Illuminate\Support\Facades\Http;

class DecController extends Controller
{
    public function detail($id)
    {
        $gempa = Gempa::findOrFail($id);

        $depth = (float) preg_replace('/[^0-9.]/', '', $gempa->kedalaman);

        $response = Http::post(
            env('DEC_API_URL') . '/predict',
            [
                'magnitudo' => (float) $gempa->magnitudo,
                'kedalaman' => $depth
            ]
        );

        if (!$response->successful()) {
            abort(500, 'Gagal mengambil data dari DEC API');
        }

        $dec = $response->json();

        return view('admin.dec_detail', [

            'g' => $gempa,

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
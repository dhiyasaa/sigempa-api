<?php

namespace App\Http\Controllers;

use App\Models\Gempa;
use App\Services\DecRiskService;

class DecController extends Controller
{
    public function detail($id)
    {
        $gempa = Gempa::findOrFail($id);

        $mag = (float) $gempa->magnitudo;
        $depth = (int) str_replace(' km', '', $gempa->kedalaman);

        // hanya untuk ditampilkan
        $Mmin = 2;
        $Mmax = 8;
        $Dmin = 0;
        $Dmax = 300;

        $mNorm = ($mag - $Mmin) / ($Mmax - $Mmin);
        $dNorm = ($depth - $Dmin) / ($Dmax - $Dmin);

        $riskService = new DecRiskService();

        $result = $riskService->predictStatus($mag, $depth);

        return view('admin.dec_detail', [
            'gempa' => $gempa,
            'mag' => $mag,
            'depth' => $depth,
            'mNorm' => round($mNorm,3),
            'dNorm' => round($dNorm,3),

            'cluster' => $result['label'],
            'status' => $result['status'],
            'color' => $result['color'],
        ]);
    }
}
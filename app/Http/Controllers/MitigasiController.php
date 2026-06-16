<?php

namespace App\Http\Controllers;

use App\Models\Gempa;

class MitigasiController extends Controller
{
    public function index()
    {
        $gempaTerbaru = Gempa::where('source', 'BMKG')
            ->latest()
            ->first();

        return view('admin.mitigasi', compact('gempaTerbaru'));
    }
}
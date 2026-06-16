<?php

namespace App\Http\Controllers;

use App\Models\Gempa;
use App\Http\Controllers\Controller;

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
    public function decDetail($id) {
        $g = Gempa::findOrFail($id);

        // parsing
        $mag = floatval($g->magnitudo);
        $depth = intval(str_replace(' km','',$g->kedalaman));

        // ===== NORMALISASI
        $Mmin=2; $Mmax=8;
        $Dmin=0; $Dmax=300;

        $mNorm = ($mag - $Mmin)/($Mmax - $Mmin);
        $dNorm = ($depth - $Dmin)/($Dmax - $Dmin);

        // ===== CENTROID
        $c_tinggi = [0.9, 0.1];
        $c_sedang = [0.6, 0.5];
        $c_rendah = [0.3, 0.8];

        // ===== DISTANCE
        $dist = function($m,$d,$c){
            return sqrt(pow($m-$c[0],2)+pow($d-$c[1],2));
        };

        $dTinggi = $dist($mNorm,$dNorm,$c_tinggi);
        $dSedang = $dist($mNorm,$dNorm,$c_sedang);
        $dRendah = $dist($mNorm,$dNorm,$c_rendah);

        // ===== RESULT
        $min = min($dTinggi,$dSedang,$dRendah);

        if($min==$dTinggi){ $cluster='TINGGI'; $status='SIAGA'; }
        elseif($min==$dSedang){ $cluster='SEDANG'; $status='WASPADA'; }
        else { $cluster='RENDAH'; $status='AMAN'; }

        return view('admin.dec_detail', compact(
            'g','mag','depth','mNorm','dNorm',
            'c_tinggi','c_sedang','c_rendah',
            'dTinggi','dSedang','dRendah',
            'cluster','status'
        ));
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Gempa;

class DecController extends Controller
{
    public function detail($id)
    {
        $gempa = Gempa::findOrFail($id);

        $mag = (float)$gempa->magnitudo;
        $depth = (int)str_replace(' km','',$gempa->kedalaman);

        // NORMALISASI
        $Mmin=2; $Mmax=8;
        $Dmin=0; $Dmax=300;

        $mNorm = ($mag - $Mmin)/($Mmax - $Mmin);
        $dNorm = ($depth - $Dmin)/($Dmax - $Dmin);

        // CENTROID
        $c_tinggi = [0.9, 0.1];
        $c_sedang = [0.6, 0.5];
        $c_rendah = [0.3, 0.8];

        $dist = fn($m,$d,$c)=>sqrt(pow($m-$c[0],2)+pow($d-$c[1],2));

        $dTinggi = $dist($mNorm,$dNorm,$c_tinggi);
        $dSedang = $dist($mNorm,$dNorm,$c_sedang);
        $dRendah = $dist($mNorm,$dNorm,$c_rendah);

        $min = min($dTinggi,$dSedang,$dRendah);

        if($min==$dTinggi){
            $status='SIAGA';
            $color='#dc3545';
        } elseif($min==$dSedang){
            $status='WASPADA';
            $color='#ffc107';
        } else {
            $status='AMAN';
            $color='#28a745';
        }
        $cluster = $status;

         // SIMPAN KE DB (optional)
         // DecResult::create([
         //     'magnitudo'=>$mag,
         //     'kedalaman'=>$depth,
         //     'jarak_c1'=>$dTinggi,
         //     'jarak_c2'=>$dSedang,
         //     'jarak_c3'=>$dRendah,
         //     'cluster'=>$cluster
         // ]);

       return view('admin.dec_detail', compact(
    'gempa',
    'mag',
    'depth',
    'status',
    'color',
    'mNorm',
    'dNorm',
    'c_tinggi',
    'c_sedang',
    'c_rendah',
    'dTinggi',
    'dSedang',
    'dRendah',
    'cluster'
    
));
    }
}
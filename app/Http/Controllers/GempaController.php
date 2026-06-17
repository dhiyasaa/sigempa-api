<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gempa;
use App\Models\UploadGempa;
use App\Models\ClusterCentroid;
use Maatwebsite\Excel\Facades\Excel;

class GempaController extends Controller
{
    public function index()
    {
        $data = Gempa::latest()->get();

        return view('admin.gempa', compact('data'));
    }

    public function delete($id)
    {
        Gempa::find($id)?->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus!');
    }

    public function history()
{
    $data = Gempa::whereIn('source', ['BMKG', 'DUMMY'])
        ->latest()
        ->get()
        ->map(function ($g) {
            $dec = $this->hitungDEC($g->magnitudo, $g->kedalaman);

            if (!$g->status || !$g->color) {
                $g->update([
                    'status' => $dec['status'],
                    'color' => $dec['color'],
                ]);
            }

            return [
                'id' => $g->id,
                'tanggal' => $g->tanggal,
                'jam' => $g->jam,
                'magnitudo' => (float) $g->magnitudo,
                'kedalaman' => $this->ambilAngkaKedalaman($g->kedalaman),
                'wilayah' => $g->wilayah,
                'status' => $g->status ?? $dec['status'],
                'color' => $g->color ?? $dec['color'],
                'source' => $g->source,
            ];
        });

    return view('admin.history', compact('data'));
}

    public function map()
    {
        $data = Gempa::latest()->take(100)->get();

        return view('admin.map', compact('data'));
    }

    public function api()
{
    return Gempa::where('source', 'BMKG')
        ->latest()
        ->get();
}

    public function uploadForm()
    {
        $previewData = UploadGempa::latest()->take(50)->get();
        $totalData = UploadGempa::count();

        $processedCount = UploadGempa::whereNotNull('status')->count();

        $aman = UploadGempa::where('status', 'AMAN')->count();
        $waspada = UploadGempa::where('status', 'WASPADA')->count();
        $siaga = UploadGempa::where('status', 'SIAGA')->count();

        $rendah = UploadGempa::where('magnitudo', '<', 4.5)->count();

        $sedang = UploadGempa::where('magnitudo', '>=', 4.5)
            ->where('magnitudo', '<', 5.5)
            ->count();

        $tinggi = UploadGempa::where('magnitudo', '>=', 5.5)->count();

        return view('admin.upload', compact(
            'previewData',
            'totalData',
            'processedCount',
            'aman',
            'waspada',
            'siaga',
            'rendah',
            'sedang',
            'tinggi'
        ));
    }

    public function uploadPreview(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        UploadGempa::truncate();

        $rows = Excel::toArray([], $request->file('file'))[0];

        if (count($rows) == 0) {
            return redirect('/admin/upload')->with('error', 'File Excel kosong.');
        }

        // Ambil header dari baris pertama
        $headers = array_map(function ($header) {
            return strtolower(trim($header));
        }, $rows[0]);

        // Cari index kolom berdasarkan nama header
        $tanggalIndex = array_search('tanggal', $headers);
        $jamIndex = array_search('jam', $headers);
        $lintangIndex = array_search('lintang', $headers);
        $bujurIndex = array_search('bujur', $headers);
        $magnitudoIndex = array_search('magnitudo', $headers);
        $kedalamanIndex = array_search('kedalaman', $headers);
        $wilayahIndex = array_search('wilayah', $headers);
        $potensiIndex = array_search('potensi', $headers);

        // Validasi kolom wajib
        if ($tanggalIndex === false || $jamIndex === false || $magnitudoIndex === false || $kedalamanIndex === false) {
            return redirect('/admin/upload')->with(
                'error',
                'Format Excel salah. Kolom wajib: tanggal, jam, magnitudo, kedalaman.'
            );
        }

        foreach ($rows as $index => $row) {
            if ($index == 0) {
                continue;
            }

            $tanggal = $row[$tanggalIndex] ?? null;
            $jam = $row[$jamIndex] ?? null;
            $magnitudo = $row[$magnitudoIndex] ?? null;
            $kedalaman = $row[$kedalamanIndex] ?? null;

            if (empty($tanggal) && empty($jam) && empty($magnitudo) && empty($kedalaman)) {
                continue;
            }

            UploadGempa::create([
                'tanggal' => $tanggal ?? '-',
                'jam' => $jam ?? '-',
                'lintang' => $lintangIndex !== false ? ($row[$lintangIndex] ?? null) : null,
                'bujur' => $bujurIndex !== false ? ($row[$bujurIndex] ?? null) : null,
                'magnitudo' => $magnitudo ?? null,
                'kedalaman' => $kedalaman ?? null,
                'wilayah' => $wilayahIndex !== false ? ($row[$wilayahIndex] ?? '-') : '-',
                'potensi' => $potensiIndex !== false ? ($row[$potensiIndex] ?? '-') : '-',
            ]);
        }

        return redirect('/admin/upload')
            ->with('success', 'Excel berhasil diupload. Data dibaca berdasarkan nama kolom.');
    }

    public function processDec()
    {
        if (UploadGempa::count() == 0) {
            return redirect('/admin/upload')->with('error', 'Belum ada data Excel yang diupload.');
        }

        UploadGempa::chunk(500, function ($rows) {
            foreach ($rows as $row) {
                $dec = $this->hitungDEC($row->magnitudo, $row->kedalaman);

                $row->update([
                    'status' => $dec['status'],
                    'color' => $dec['color'],
                ]);
            }
        });

        return redirect('/admin/upload')->with('success', 'Semua data berhasil diproses berdasarkan centroid final.');
    }

    public function saveUploadToDatabase()
    {
        if (UploadGempa::whereNotNull('status')->count() == 0) {
            return redirect('/admin/upload')->with('error', 'Data belum diproses.');
        }

        UploadGempa::whereNotNull('status')->chunk(500, function ($rows) {
            foreach ($rows as $row) {
                Gempa::create([
                    'tanggal' => $row->tanggal,
                    'jam' => $row->jam,
                    'lintang' => $row->lintang,
                    'bujur' => $row->bujur,
                    'magnitudo' => $row->magnitudo,
                    'kedalaman' => $row->kedalaman,
                    'wilayah' => $row->wilayah,
                    'potensi' => $row->potensi,
                    'status' => $row->status,
                    'color' => $row->color,
                    'source' => 'EXCEL',
                ]);
            }
        });

        UploadGempa::truncate();

        return redirect('/admin/gempa')
            ->with('success', 'Data Excel berhasil dimasukkan ke Data Gempa.');
    }

    public function clearUpload()
    {
        UploadGempa::truncate();

        return redirect('/admin/upload')
            ->with('success', 'Data upload sementara berhasil dibersihkan.');
    }

    private function hitungDEC($mag, $depth)
    {
        $mag = (float) $mag;
        $depth = $this->ambilAngkaKedalaman($depth);

        $centroids = ClusterCentroid::all();

        if ($centroids->count() == 0) {
            return [
                'cluster' => '-',
                'label' => '-',
                'status' => '-',
                'color' => '#6c757d',
                'jarak' => 0
            ];
        }

        $closest = null;
        $minDistance = null;

        foreach ($centroids as $c) {
            $distance = sqrt(
                pow($mag - $c->magnitudo, 2) +
                pow($depth - $c->kedalaman, 2)
            );

            if ($minDistance === null || $distance < $minDistance) {
                $minDistance = $distance;
                $closest = $c;
            }
        }

        return [
            'cluster' => $closest->cluster,
            'label' => $closest->label,
            'status' => $closest->status,
            'color' => $closest->color,
            'jarak' => round($minDistance, 3)
        ];
    }

    private function ambilAngkaKedalaman($depth)
    {
        return (int) preg_replace('/[^0-9]/', '', (string) $depth);
    }
}
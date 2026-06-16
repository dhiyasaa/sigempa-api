<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Gempa;
use App\Models\ClusterCentroid;

class FetchGempa extends Command
{
    protected $signature = 'gempa:fetch';
    protected $description = 'Fetch data gempa BMKG sekali jalan';

    public function handle()
    {
        try {
            $response = Http::timeout(10)->get(
                'https://data.bmkg.go.id/DataMKG/TEWS/gempaterkini.json'
            );

            $data = $response['Infogempa']['gempa'];

            foreach ($data as $gempa) {
                $cek = Gempa::where('tanggal', $gempa['Tanggal'])
                    ->where('jam', $gempa['Jam'])
                    ->where('source', 'BMKG')
                    ->first();

                if (!$cek) {
                    $mag = (float) $gempa['Magnitude'];
                    $depth = (int) preg_replace('/[^0-9]/', '', $gempa['Kedalaman']);

                    $cluster = $this->hitungCluster($mag, $depth);

                    Gempa::create([
                        'tanggal' => $gempa['Tanggal'],
                        'jam' => $gempa['Jam'],
                        'lintang' => $gempa['Lintang'],
                        'bujur' => $gempa['Bujur'],
                        'magnitudo' => $mag,
                        'kedalaman' => $gempa['Kedalaman'],
                        'wilayah' => $gempa['Wilayah'],
                        'potensi' => $gempa['Potensi'] ?? '-',
                        'status' => $cluster['status'],
                        'color' => $cluster['color'],
                        'source' => 'BMKG'
                    ]);

                    $this->info('Gempa baru masuk: ' . $cluster['status']);
                }
            }

            $this->info('Fetch selesai.');

        } catch (\Exception $e) {
            $this->error('Gagal fetch: ' . $e->getMessage());
        }
    }

    private function hitungCluster($mag, $depth)
    {
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
            'jarak' => $minDistance
        ];
    }
}
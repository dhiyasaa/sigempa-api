<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Gempa;
use App\Models\ClusterCentroid;

class FetchGempa extends Command
{
    protected $signature = 'gempa:fetch';
    protected $description = 'Fetch data gempa terbaru dari BMKG';

    public function handle()
    {
        $url = 'https://data.bmkg.go.id/DataMKG/TEWS/autogempa.json';

        try {

            $response = Http::withOptions([
                'verify' => false,
            ])
                ->timeout(20)
                ->get($url);

            if (!$response->successful()) {
                $this->line('ERROR');
                return Command::FAILURE;
            }

            $json = $response->json();

            if (!isset($json['Infogempa']['gempa'])) {
                $this->line('ERROR');
                return Command::FAILURE;
            }

            $gempa = $json['Infogempa']['gempa'];

            $tanggal = $gempa['Tanggal'] ?? '-';
            $jam = $gempa['Jam'] ?? '-';
            $lintang = $gempa['Lintang'] ?? '-';
            $bujur = $gempa['Bujur'] ?? '-';
            $magnitudo = $gempa['Magnitude'] ?? '0';
            $kedalaman = $gempa['Kedalaman'] ?? '0 km';
            $wilayah = $gempa['Wilayah'] ?? '-';
            $potensi = $gempa['Potensi'] ?? 'Tidak ada informasi potensi';

            $sudahAda = Gempa::where('tanggal', $tanggal)
                ->where('jam', $jam)
                ->where('wilayah', $wilayah)
                ->where('source', 'BMKG')
                ->exists();

            if ($sudahAda) {
                $this->line('NO_NEW_DATA');
                return Command::SUCCESS;
            }

            $mag = (float) str_replace(',', '.', $magnitudo);
            $depth = (int) preg_replace('/[^0-9]/', '', $kedalaman);

            $cluster = $this->hitungCluster($mag, $depth);

            Gempa::create([
                'tanggal' => $tanggal,
                'jam' => $jam,
                'lintang' => $lintang,
                'bujur' => $bujur,
                'magnitudo' => $magnitudo,
                'kedalaman' => $kedalaman,
                'wilayah' => $wilayah,
                'potensi' => $potensi,
                'status' => $cluster['status'],
                'color' => $cluster['color'],
                'source' => 'BMKG',
            ]);

            $this->line('NEW_DATA');

            return Command::SUCCESS;

        } catch (\Throwable $e) {

            $this->line('ERROR');

            return Command::FAILURE;
        }
    }

    private function hitungCluster($mag, $depth)
    {
        $centroids = ClusterCentroid::all();

        if ($centroids->count() == 0) {
            return $this->statusFallback($mag);
        }

        $closest = null;
        $minDistance = null;

        foreach ($centroids as $c) {

            $distance = sqrt(
                pow($mag - (float) $c->magnitudo, 2) +
                pow($depth - (float) $c->kedalaman, 2)
            );

            if ($minDistance === null || $distance < $minDistance) {
                $minDistance = $distance;
                $closest = $c;
            }
        }

        if (!$closest) {
            return $this->statusFallback($mag);
        }

        return [
            'cluster' => $closest->cluster ?? '-',
            'label' => $closest->label ?? '-',
            'status' => $closest->status ?? $this->statusFallback($mag)['status'],
            'color' => $closest->color ?? $this->statusFallback($mag)['color'],
            'jarak' => $minDistance ?? 0,
        ];
    }

    private function statusFallback($mag)
    {
        if ($mag >= 6.0) {
            return [
                'cluster' => '-',
                'label' => 'Bahaya Tinggi',
                'status' => 'SIAGA',
                'color' => '#EF4444',
                'jarak' => 0,
            ];
        }

        if ($mag >= 5.0) {
            return [
                'cluster' => '-',
                'label' => 'Bahaya Sedang',
                'status' => 'WASPADA',
                'color' => '#FACC15',
                'jarak' => 0,
            ];
        }

        return [
            'cluster' => '-',
            'label' => 'Bahaya Rendah',
            'status' => 'AMAN',
            'color' => '#22C55E',
            'jarak' => 0,
        ];
    }
}
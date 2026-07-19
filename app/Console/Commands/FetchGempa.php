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

           $decResponse = Http::timeout(20)->post(
    env('DEC_API_URL') . '/predict',
    [
        'magnitudo' => $mag,
        'kedalaman' => $depth
    ]
);

if (!$decResponse->successful()) {
    $this->line('ERROR');
    return Command::FAILURE;
}

$cluster = $decResponse->json();

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

}
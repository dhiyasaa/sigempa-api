<?php

namespace App\Services;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\Http;

class DecRiskService
{
    public function predictStatus($mag, $depth)
{
    $response = Http::timeout(30)->post(
        env('DEC_API_URL') . '/predict',
        [
            'magnitudo' => (float) $mag,
            'kedalaman' => (float) $depth,
        ]
    );

    if (!$response->successful()) {
        throw new \Exception(
            'DEC API Error: ' . $response->body()
        );
    }

    $result = $response->json();

    return [
        'cluster'    => $result['cluster'],
        'label'      => $result['label'],
        'status'     => $result['status'],
        'color'      => $result['color'],
        'magnitudo'  => $mag,
        'kedalaman'  => $depth,
    ];
}

    private function ambilAngkaMagnitudo($magnitudo): float
    {
        $clean = str_replace(',', '.', (string) $magnitudo);

        if (preg_match('/-?\d+(\.\d+)?/', $clean, $match)) {
            return (float) $match[0];
        }

        return 0;
    }

    private function ambilAngkaKedalaman($kedalaman): int
    {
        $angka = preg_replace('/[^0-9]/', '', (string) $kedalaman);

        return $angka !== '' ? (int) $angka : 0;
    }
}
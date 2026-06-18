<?php

namespace App\Services;

use App\Models\ClusterCentroid;

class DecRiskService
{
    public function predictStatus($magnitudo, $kedalaman): array
    {
        $mag = $this->ambilAngkaMagnitudo($magnitudo);
        $depth = $this->ambilAngkaKedalaman($kedalaman);

        $centroids = ClusterCentroid::all();

        if ($centroids->count() == 0) {
            return $this->fallbackStatus($mag, $depth);
        }

        $closest = null;
        $minDistance = null;

        foreach ($centroids as $centroid) {
            $distance = sqrt(
                pow($mag - (float) $centroid->magnitudo, 2) +
                pow($depth - (float) $centroid->kedalaman, 2)
            );

            if ($minDistance === null || $distance < $minDistance) {
                $minDistance = $distance;
                $closest = $centroid;
            }
        }

        $status = strtoupper($closest->status ?? '-');
        $color = $closest->color ?? $this->warnaStatus($status);

        return [
            'cluster' => $closest->cluster ?? '-',
            'label' => $closest->label ?? '-',
            'status' => $status,
            'color' => $color,
            'jarak' => round($minDistance, 3),
            'magnitudo' => $mag,
            'kedalaman' => $depth,
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

    private function warnaStatus(string $status): string
    {
        return match (strtoupper($status)) {
            'SIAGA' => '#EF4444',
            'WASPADA' => '#FACC15',
            'AMAN' => '#22C55E',
            default => '#6B7280',
        };
    }

    private function fallbackStatus(float $mag, int $depth): array
    {
        if ($mag >= 5.5 && $depth <= 70) {
            $status = 'SIAGA';
            $cluster = 'C2';
            $label = 'Tinggi';
        } elseif ($mag >= 4.5) {
            $status = 'WASPADA';
            $cluster = 'C1';
            $label = 'Sedang';
        } else {
            $status = 'AMAN';
            $cluster = 'C0';
            $label = 'Rendah';
        }

        return [
            'cluster' => $cluster,
            'label' => $label,
            'status' => $status,
            'color' => $this->warnaStatus($status),
            'jarak' => 0,
            'magnitudo' => $mag,
            'kedalaman' => $depth,
        ];
    }
}
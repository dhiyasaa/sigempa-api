<?php

namespace App\Services;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class DecRiskService
{
    public function predictStatus($magnitudo, $kedalaman): array
    {
        $mag = $this->ambilAngkaMagnitudo($magnitudo);
        $depth = $this->ambilAngkaKedalaman($kedalaman);

        $pythonScript = base_path('app/Services/Python/predict_dec.py');

        $process = new Process([
            'py',
            '-3.11',
            $pythonScript,
            $mag,
            $depth
        ]);

        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $result = json_decode($process->getOutput(), true);

        if (!$result) {
            throw new \Exception('Output JSON dari predict_dec.py tidak valid.');
        }

        return [
            'cluster' => $result['cluster'],
            'label' => $result['label'],
            'status' => $result['status'],
            'color' => $result['color'],
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
}
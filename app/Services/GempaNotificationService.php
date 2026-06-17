<?php

namespace App\Services;

use App\Models\FcmToken;
use App\Models\Gempa;
use Illuminate\Support\Facades\Log;

class GempaNotificationService
{
    public function sendGempaNotification(Gempa $gempa): void
    {
        $gempaLat = $this->parseLintang($gempa->lintang);
        $gempaLng = $this->parseBujur($gempa->bujur);

        if ($gempaLat === null || $gempaLng === null) {
            Log::warning('Koordinat gempa tidak valid untuk FCM.', [
                'gempa_id' => $gempa->id,
                'lintang' => $gempa->lintang,
                'bujur' => $gempa->bujur,
            ]);

            return;
        }

        $tokens = FcmToken::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        if ($tokens->count() === 0) {
            Log::info('Tidak ada token FCM yang tersimpan.');
            return;
        }

        $fcmService = new FcmService();

        foreach ($tokens as $item) {
            $jarak = $this->hitungJarakKm(
                (float) $item->latitude,
                (float) $item->longitude,
                $gempaLat,
                $gempaLng
            );

            $radius = (float) ($item->radius_km ?? 100);

            if ($jarak <= $radius) {
                $status = $gempa->status ?: 'SIAGA';

                $title = match (strtoupper($status)) {
                    'SIAGA' => '🚨 PERINGATAN DINI GEMPA BUMI',
                    'WASPADA' => '⚠️ PERINGATAN GEMPA',
                    'AMAN' => 'ℹ️ INFO GEMPA TERKINI',
                    default => '🚨 PERINGATAN GEMPA',
                };

                $body = 'Gempa terdeteksi dalam radius ' . round($radius) . ' km dari lokasi kamu.';

                $fcmService->sendToToken($item->token, [
                    'title' => $title,
                    'body' => $body,
                    'gempa_id' => $gempa->id,
                    'tanggal' => $gempa->tanggal,
                    'jam' => $gempa->jam,
                    'wilayah' => $gempa->wilayah,
                    'magnitudo' => $gempa->magnitudo,
                    'kedalaman' => $gempa->kedalaman,
                    'status' => $status,
                    'jarak_km' => number_format($jarak, 1, '.', ''),
                ]);

                Log::info('FCM diproses untuk token dalam radius.', [
                    'gempa_id' => $gempa->id,
                    'jarak_km' => number_format($jarak, 1, '.', ''),
                    'radius_km' => $radius,
                ]);
            } else {
                Log::info('Token dilewati karena di luar radius.', [
                    'gempa_id' => $gempa->id,
                    'jarak_km' => number_format($jarak, 1, '.', ''),
                    'radius_km' => $radius,
                ]);
            }
        }
    }

    private function parseLintang($value): ?float
    {
        $clean = strtoupper(str_replace(',', '.', (string) $value));

        if (!preg_match('/-?\d+(\.\d+)?/', $clean, $match)) {
            return null;
        }

        $angka = abs((float) $match[0]);

        if (str_contains($clean, 'LS')) {
            return -$angka;
        }

        return $angka;
    }

    private function parseBujur($value): ?float
    {
        $clean = strtoupper(str_replace(',', '.', (string) $value));

        if (!preg_match('/-?\d+(\.\d+)?/', $clean, $match)) {
            return null;
        }

        $angka = abs((float) $match[0]);

        if (str_contains($clean, 'BB')) {
            return -$angka;
        }

        return $angka;
    }

    private function hitungJarakKm($lat1, $lon1, $lat2, $lon2): float
    {
        $earthRadius = 6371;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a =
            sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) *
            cos(deg2rad($lat2)) *
            sin($dLon / 2) *
            sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
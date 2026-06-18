<?php

namespace App\Services;

use Google\Auth\Credentials\ServiceAccountCredentials;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FcmService
{
    public function sendToToken(string $token, array $data): bool
    {
        try {
            $projectId = env('FCM_PROJECT_ID');

            if (!$projectId) {
                Log::error('FCM_PROJECT_ID belum diisi.');
                return false;
            }

            $accessToken = $this->getAccessToken();

            if (!$accessToken) {
                Log::error('Access token FCM gagal dibuat.');
                return false;
            }

            $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

            $title = $data['title'] ?? '🚨 PERINGATAN GEMPA';
            $body = $data['body'] ?? 'Gempa terdeteksi dalam radius peringatan kamu.';
            $notificationBody = $data['notification_body'] ?? $body;

            $payload = [
                'message' => [
                    'token' => $token,

                    'notification' => [
                        'title' => (string) $title,
                        'body' => (string) $notificationBody,
                    ],

                    'data' => $this->stringifyData($data),

                    'android' => [
                        'priority' => 'HIGH',
                        'notification' => [
                            'channel_id' => 'gempa_alarm_channel_v5',
                            'sound' => 'default',
                            'click_action' => 'OPEN_GEMPA_DETAIL',
                        ],
                    ],
                ],
            ];

            $response = Http::withToken($accessToken)
                ->acceptJson()
                ->asJson()
                ->post($url, $payload);

            if (!$response->successful()) {
                Log::error('FCM send gagal', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return false;
            }

            Log::info('FCM berhasil dikirim.', [
                'token' => substr($token, 0, 20) . '...',
            ]);

            return true;

        } catch (\Throwable $e) {
            Log::error('FCM exception: ' . $e->getMessage());
            return false;
        }
    }

    private function getAccessToken(): ?string
    {
        try {
            $base64 = env('FIREBASE_CREDENTIALS_BASE64');

            if (!$base64) {
                Log::error('FIREBASE_CREDENTIALS_BASE64 belum diisi.');
                return null;
            }

            $json = base64_decode($base64);

            if (!$json) {
                Log::error('FIREBASE_CREDENTIALS_BASE64 tidak valid.');
                return null;
            }

            $credentialsArray = json_decode($json, true);

            if (!$credentialsArray) {
                Log::error('Service account JSON tidak valid.');
                return null;
            }

            $credentials = new ServiceAccountCredentials(
                ['https://www.googleapis.com/auth/firebase.messaging'],
                $credentialsArray
            );

            $token = $credentials->fetchAuthToken();

            return $token['access_token'] ?? null;

        } catch (\Throwable $e) {
            Log::error('Gagal membuat access token FCM: ' . $e->getMessage());
            return null;
        }
    }

    private function stringifyData(array $data): array
    {
        $result = [];

        foreach ($data as $key => $value) {
            $result[$key] = (string) $value;
        }

        return $result;
    }
}
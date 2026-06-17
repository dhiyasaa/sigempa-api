<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FcmToken;

class FcmTokenController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'token' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius_km' => 'nullable|numeric',
        ]);

        FcmToken::updateOrCreate(
            [
                'token' => $validated['token'],
            ],
            [
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
                'radius_km' => $validated['radius_km'] ?? 100,
                'last_seen_at' => now(),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Token FCM berhasil disimpan.',
        ]);
    }
}
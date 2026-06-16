<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GempaController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\EdukasiController;
use App\Http\Controllers\UmpanBalikController;

Route::get('/gempa', [GempaController::class, 'api']);
Route::get('/berita', [BeritaController::class, 'api']);
Route::get('/edukasi', [EdukasiController::class, 'api']);
Route::post('/umpan-balik', [UmpanBalikController::class, 'storeApi']);

Route::get('/test-umpan-balik', function () {
    return response()->json([
        'success' => true,
        'message' => 'API umpan balik aktif'
    ]);
});
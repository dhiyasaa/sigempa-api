<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GempaController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\EdukasiController;
use App\Http\Controllers\UmpanBalikController;
use App\Http\Controllers\FcmTokenController;

Route::get('/gempa', [GempaController::class, 'api'])
    ->name('api.gempa');

Route::get('/berita', [BeritaController::class, 'api'])
    ->name('api.berita');

Route::get('/edukasi', [EdukasiController::class, 'api'])
    ->name('api.edukasi');

Route::post('/umpan-balik', [UmpanBalikController::class, 'storeApi'])
    ->name('api.umpanBalik');

Route::post('/fcm-token', [FcmTokenController::class, 'store'])
    ->name('api.fcmToken');
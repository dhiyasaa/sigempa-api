<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Models\Gempa;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GempaController;
use App\Http\Controllers\MitigasiController;
use App\Http\Controllers\DecController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\EdukasiController;
use App\Http\Controllers\UmpanBalikController;

Route::get('/', function () {
    return redirect('/admin');
});

// AUTH
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// ===============================
// API UNTUK ANDROID
// ===============================
Route::get('/api/gempa', [GempaController::class, 'api'])->name('api.gempa');

Route::get('/api/gempa-terbaru', function () {
    return response()->json(
        Gempa::orderBy('id', 'desc')->first()
    );
})->name('api.gempaTerbaru');

Route::get('/api/berita', [BeritaController::class, 'api'])->name('api.berita');
Route::get('/api/edukasi', [EdukasiController::class, 'api'])->name('api.edukasi');

Route::post('/api/umpan-balik', [UmpanBalikController::class, 'storeApi'])
    ->name('api.umpanBalik');


// ===============================
// ADMIN AREA WAJIB LOGIN
// ===============================
Route::middleware('auth')->group(function () {

    // DASHBOARD
    Route::get('/admin', [AdminController::class, 'dashboard'])
        ->name('admin.dashboard');

    // DATA GEMPA
    Route::get('/admin/gempa', [GempaController::class, 'index'])
        ->name('admin.gempa');

    Route::delete('/admin/gempa/{id}', [GempaController::class, 'delete'])
        ->name('admin.gempa.delete');

    // HISTORY GEMPA
    Route::get('/admin/history', [GempaController::class, 'history'])
        ->name('admin.history');

    // MAP
    Route::get('/admin/map', [GempaController::class, 'map'])
        ->name('admin.map');

    // MITIGASI
    Route::get('/admin/mitigasi', [MitigasiController::class, 'index'])
        ->name('admin.mitigasi');

    // DETAIL DEC
    Route::get('/admin/dec/{id}', [DecController::class, 'detail'])
        ->name('admin.dec.detail');


    // ===============================
    // REFRESH BMKG MANUAL
    // ===============================
    Route::get('/admin/refresh', function () {
        $exitCode = Artisan::call('gempa:fetch');
        $output = trim(Artisan::output());

        if ($exitCode === 0) {
            return redirect('/admin/history')
                ->with('success', $output ?: 'Data BMKG berhasil di-refresh!');
        }

        return redirect('/admin/history')
            ->with('success', $output ?: 'Gagal refresh data BMKG.');
    })->name('admin.refresh');


    // ===============================
    // REFRESH BMKG JSON UNTUK AUTO FETCH
    // ===============================
    Route::get('/admin/refresh-json', function () {
        $beforeId = Gempa::max('id');

        $exitCode = Artisan::call('gempa:fetch');
        $output = trim(Artisan::output());

        $afterId = Gempa::max('id');

        return response()->json([
            'success' => $exitCode === 0,
            'message' => $output ?: 'Auto fetch selesai.',
            'before_id' => $beforeId,
            'after_id' => $afterId,
            'has_new_data' => $afterId != $beforeId,
        ]);
    })->name('admin.refreshJson');


    // AUTO FETCH INFO
    Route::get('/admin/auto-fetch-info', function () {
        return redirect('/admin/history')
            ->with('success', 'Auto Fetch aktif selama halaman History Gempa dibuka.');
    })->name('admin.autoFetch');


    // UPLOAD EXCEL
    Route::get('/admin/upload', [GempaController::class, 'uploadForm'])
        ->name('admin.upload');

    Route::post('/admin/upload/preview', [GempaController::class, 'uploadPreview'])
        ->name('admin.upload.preview');

    Route::post('/admin/upload/process-dec', [GempaController::class, 'processDec'])
        ->name('admin.upload.processDec');

    Route::post('/admin/upload/save', [GempaController::class, 'saveUploadToDatabase'])
        ->name('admin.upload.save');

    Route::post('/admin/upload/clear', [GempaController::class, 'clearUpload'])
        ->name('admin.upload.clear');


    // BERITA GEMPA
    Route::get('/admin/berita', [BeritaController::class, 'index'])
        ->name('admin.berita');

    Route::post('/admin/berita', [BeritaController::class, 'store'])
        ->name('admin.berita.store');

    Route::delete('/admin/berita/{id}', [BeritaController::class, 'delete'])
        ->name('admin.berita.delete');


    // EDUKASI GEMPA
    Route::get('/admin/edukasi', [EdukasiController::class, 'index'])
        ->name('admin.edukasi');

    Route::post('/admin/edukasi', [EdukasiController::class, 'store'])
        ->name('admin.edukasi.store');

    Route::delete('/admin/edukasi/{id}', [EdukasiController::class, 'delete'])
        ->name('admin.edukasi.delete');


    // UMPAN BALIK
    Route::get('/admin/umpan-balik', [UmpanBalikController::class, 'index'])
        ->name('admin.umpanBalik');

    Route::delete('/admin/umpan-balik/{id}', [UmpanBalikController::class, 'delete'])
        ->name('admin.umpanBalik.delete');
});
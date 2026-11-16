<?php

use App\Http\Controllers\Api\PasienController;
use App\Http\Controllers\Api\AntreanController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/pasien/baru', [PasienController::class, 'registrasiBaru']);

Route::post('/pasien/lama', [PasienController::class, 'registrasiLama']);

Route::post('/pasien/masuk', [PasienController::class, 'cekNik'])
    ->middleware('throttle:10,1');

Route::post('/pasien/masuk/konfirmasi', [PasienController::class, 'konfirmasiMasuk']);

Route::post('/antrean/batal', [AntreanController::class, 'batalkanOlehPasien']);

Route::get('/antrean/status/{token}', [AntreanController::class, 'status'])
    ->middleware('throttle:30,1'); // 30/menit bukan anti-brute-force, cuma jaga-jaga polling nakal

Route::post('/antrean/{id}/batal', [AntreanController::class, 'batalkanOlehAdmin'])
    ->middleware('auth:sanctum');

//Login Aadmin
Route::post('/admin/login', [AuthController::class, 'login'])->middleware('throttle:20,1');

Route::prefix('admin')->middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/antrean/{id}/batal', [AntreanController::class, 'batalkanOlehAdmin']);
    // Sprint 4: panggil, ubah status, sisipan masuk grup ini
});
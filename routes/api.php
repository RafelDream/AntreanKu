<?php

use App\Http\Controllers\Api\PasienController;
use App\Http\Controllers\Api\AntreanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/pasien/baru', [PasienController::class, 'registrasiBaru']);
Route::post('/pasien/lama', [PasienController::class, 'registrasiLama']);
Route::post('/pasien/masuk', [PasienController::class, 'cekNik']);
Route::post('/pasien/masuk/konfirmasi', [PasienController::class, 'konfirmasiMasuk']);

Route::post('/antrean/batal', [AntreanController::class, 'batalkanOlehPasien']);

// Middleware auth:sanctum baru aktif penuh setelah Sprint 3 (login admin) selesai
Route::post('/antrean/{id}/batal', [AntreanController::class, 'batalkanOlehAdmin'])
    ->middleware('auth:sanctum');
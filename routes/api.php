<?php

use App\Http\Controllers\Api\PasienController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/pasien/baru', [PasienController::class, 'registrasiBaru']);
Route::post('/pasien/lama', [PasienController::class, 'registrasiLama']);
Route::post('/pasien/masuk', [PasienController::class, 'cekNik']);
Route::post('/pasien/masuk/konfirmasi', [PasienController::class, 'konfirmasiMasuk']);
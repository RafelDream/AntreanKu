<?php

use App\Http\Controllers\Web\PendaftaranController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'beranda')->name('beranda');

Route::controller(PendaftaranController::class)->group(function () {
    // Pasien baru
    Route::get('/daftar', 'formBaru')->name('daftar');
    Route::post('/daftar', 'simpanBaru')->name('daftar.simpan')->middleware('throttle:20,1');

    // Pasien lama: masuk via NIK
    Route::get('/masuk', 'formMasuk')->name('masuk');
    Route::post('/masuk', 'cekMasuk')->name('masuk.cek')->middleware('throttle:20,1');

    // Pasien lama: "Lupa NIK?" -> No. RM + tanggal lahir
    Route::get('/masuk/lupa-nik', 'formLupaNik')->name('masuk.lupa');
    Route::post('/masuk/lupa-nik', 'cekLupaNik')->name('masuk.lupa.cek')->middleware('throttle:10,1');

    // Konfirmasi identitas (popup) -> terbitkan antrean
    Route::post('/masuk/konfirmasi', 'konfirmasiMasuk')->name('masuk.konfirmasi')->middleware('throttle:20,1');

    // Tiket antrean (diakses lewat token acak, bukan ID berurutan)
    Route::get('/antrean/{token}', 'tiket')
        ->name('antrean.tiket')->where('token', '[A-Za-z0-9]{32}');
    Route::get('/antrean/{token}/status', 'status')
        ->name('antrean.status')->where('token', '[A-Za-z0-9]{32}')->middleware('throttle:60,1');
});

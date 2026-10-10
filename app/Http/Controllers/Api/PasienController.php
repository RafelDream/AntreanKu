<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CekNikRequest;
use App\Http\Requests\KonfirmasiMasukRequest;
use App\Http\Requests\RegistrasiPasienBaruRequest;
use App\Http\Requests\RegistrasiPasienLamaRequest;
use App\Models\Antrean;
use App\Models\Pasien;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PasienController extends Controller
{
    public function registrasiBaru(RegistrasiPasienBaruRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $pasien = Pasien::create([
                'nama_lengkap'  => $request->nama_lengkap,
                'jenis_pasien'  => 'baru',
                'nik'           => $request->nik,
                'tanggal_lahir' => $request->tanggal_lahir,
                'no_telepon'    => $request->no_telepon,
                'alamat'        => $request->alamat,
            ]);

            // Auto-generate No. RM, format mudah diingat: RM0001
            $pasien->update([
                'no_rekam_medis' => 'RM' . str_pad($pasien->id, 4, '0', STR_PAD_LEFT),
            ]);

            $antrean = $this->terbitkanNomorAntrean($pasien->id);

            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil',
                'data' => [
                    'no_rekam_medis' => $pasien->no_rekam_medis,
                    'nomor_antrean'  => $antrean->nomor_antrean,
                    'token'          => $antrean->token,
                ],
            ], 201);
        });
    }

    public function registrasiLama(RegistrasiPasienLamaRequest $request)
    {
        $pasien = Pasien::where('no_rekam_medis', $request->no_rekam_medis)
            ->where('tanggal_lahir', $request->tanggal_lahir)
            ->first();

        if (! $pasien) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor Rekam Medis atau Tanggal Lahir tidak cocok',
            ], 422);
        }

        return DB::transaction(function () use ($pasien) {
            $antrean = $this->terbitkanNomorAntrean($pasien->id);

            return response()->json([
                'success' => true,
                'message' => 'Login berhasil', //Registrasi
                'data' => [
                    'nomor_antrean' => $antrean->nomor_antrean,
                    'token'         => $antrean->token,
                ],
            ], 201);
        });
    }

    public function cekNik(CekNikRequest $request)
    {
        $pasien = Pasien::where('nik', $request->nik)->first();

        if (! $pasien) {
            return response()->json([
                'success' => false,
                'message' => 'NIK belum terdaftar, silakan daftar sebagai pasien baru',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'pasien_id'    => $pasien->id,
                'nik_tersamar' => substr($pasien->nik, 0, 3) . str_repeat('x', 13),
                'nama_lengkap' => $pasien->nama_lengkap,
            ],
        ]);
    }

    public function konfirmasiMasuk(KonfirmasiMasukRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $antrean = $this->terbitkanNomorAntrean($request->pasien_id);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil masuk',
                'data' => [
                    'nomor_antrean' => $antrean->nomor_antrean,
                    'token'         => $antrean->token,
                ],
            ], 201);
        });
    }

    private function terbitkanNomorAntrean(int $pasienId): Antrean
    {
        $hariIni = now()->toDateString();

        // Cegah antrean ganda untuk pasien yang sama hari ini
        $existing = Antrean::where('pasien_id', $pasienId)
            ->where('tanggal_antrean', $hariIni)
            ->lockForUpdate()
            ->first();

        if ($existing) {
            return $existing;
        }

        // Ambil nomor antrean terakhir hari ini, kunci baris agar aman dari race condition
        $nomorTerakhir = Antrean::where('tanggal_antrean', $hariIni)
            ->lockForUpdate()
            ->max('nomor_antrean');

        return Antrean::create([
            'pasien_id'       => $pasienId,
            'nomor_antrean'   => ($nomorTerakhir ?? 0) + 1,
            'tanggal_antrean' => $hariIni,
            'status'          => 'menunggu',
            'token'           => Str::random(32),
        ]);
    }
}
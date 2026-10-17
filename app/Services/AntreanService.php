<?php

namespace App\Services;

use App\Models\Antrean;
use App\Models\Pasien;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Logika bisnis registrasi & penerbitan antrean.
 * Dipakai bersama oleh API (Api\PasienController) dan web Blade (Web\PendaftaranController)
 * supaya aturan validasi/duplikasi antrean hanya ada di satu tempat.
 */
class AntreanService
{
    public const STATUS_LABEL = [
        'menunggu'    => 'Menunggu',
        'dipanggil'   => 'Dipanggil',
        'hadir'       => 'Hadir',
        'belum_hadir' => 'Belum hadir',
        'selesai'     => 'Selesai',
        'hangus'      => 'Hangus',
        'dibatalkan'  => 'Dibatalkan',
    ];

    /** Pasien baru: simpan data, buat No. RM otomatis (RM0001), lalu terbitkan antrean. */
    public function daftarPasienBaru(array $data): array
    {
        return DB::transaction(function () use ($data) {
            $pasien = Pasien::create([
                'nama_lengkap'  => $data['nama_lengkap'],
                'jenis_pasien'  => 'baru',
                'nik'           => $data['nik'],
                'tanggal_lahir' => $data['tanggal_lahir'],
                'no_telepon'    => $data['no_telepon'] ?? null,
                'alamat'        => $data['alamat'] ?? null,
            ]);

            $pasien->update([
                'no_rekam_medis' => 'RM' . str_pad($pasien->id, 4, '0', STR_PAD_LEFT),
            ]);

            return [$pasien, $this->terbitkanNomorAntrean($pasien->id)];
        });
    }

    public function cariPasienLama(string $noRekamMedis, string $tanggalLahir): ?Pasien
    {
        return Pasien::where('no_rekam_medis', $noRekamMedis)
            ->where('tanggal_lahir', $tanggalLahir)
            ->first();
    }

    public function cariByNik(string $nik): ?Pasien
    {
        return Pasien::where('nik', $nik)->first();
    }

    /** 3 digit pertama + 13 'x', mis. 327xxxxxxxxxxxxx. null jika pasien belum punya NIK. */
    public function nikTersamar(Pasien $pasien): ?string
    {
        return $pasien->nik ? substr($pasien->nik, 0, 3) . str_repeat('x', 13) : null;
    }

    /**
     * Terbitkan nomor antrean hari ini. Jika pasien sudah punya antrean hari ini,
     * antrean yang sama dikembalikan (cek ->wasRecentlyCreated untuk membedakan).
     */
    public function terbitkanNomorAntrean(int $pasienId): Antrean
    {
        $hariIni   = now()->toDateString();
        $percobaan = 0;

        while (true) {
            try {
                return DB::transaction(function () use ($pasienId, $hariIni) {
                    // Cegah antrean ganda untuk pasien yang sama hari ini
                    $existing = Antrean::where('pasien_id', $pasienId)
                        ->where('tanggal_antrean', $hariIni)
                        ->lockForUpdate()
                        ->first();

                    if ($existing) {
                        return $existing;
                    }

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
                });
            } catch (UniqueConstraintViolationException $e) {
                // Dua pasien mengambil nomor di detik yang sama -> ulangi (maks. 3x)
                if (++$percobaan >= 3) {
                    throw $e;
                }
            }
        }
    }

    /** Data ringkas untuk halaman tiket & polling status. */
    public function ringkasan(Antrean $antrean): array
    {
        $hari = $antrean->tanggal_antrean->toDateString();

        $sedangDilayani = Antrean::where('tanggal_antrean', $hari)
            ->whereIn('status', ['dipanggil', 'hadir'])
            ->orderByDesc('updated_at')
            ->value('nomor_antrean');

        $sisaDiDepan = $antrean->status === 'menunggu'
            ? Antrean::where('tanggal_antrean', $hari)
                ->where('status', 'menunggu')
                ->where('nomor_antrean', '<', $antrean->nomor_antrean)
                ->count()
            : null;

        return [
            'nomor_antrean'   => $antrean->nomor_antrean,
            'status'          => $antrean->status,
            'status_label'    => self::STATUS_LABEL[$antrean->status] ?? $antrean->status,
            'sedang_dilayani' => $sedangDilayani,
            'sisa_di_depan'   => $sisaDiDepan,
        ];
    }
    
    public function batalkanOlehPasien(string $token): Antrean
    {
        return DB::transaction(function () use ($token) {
            $antrean = Antrean::where('token', $token)->lockForUpdate()->first();

            if (! $antrean) {
                abort(404, 'Tiket antrean tidak ditemukan');
            }

            if ($antrean->status !== 'menunggu') {
                abort(422, 'Antrean sudah dipanggil/diproses, tidak dapat dibatalkan');
            }

            $antrean->update(['status' => 'dibatalkan']);

            return $antrean;
        });
    }

    public function batalkanOlehAdmin(int $antreanId): Antrean
    {
        return DB::transaction(function () use ($antreanId) {
            $antrean = Antrean::lockForUpdate()->findOrFail($antreanId);

            // Catatan: batas status untuk admin BELUM dikonfirmasi PO — lihat poin di bawah
            if ($antrean->status === 'selesai') {
                abort(422, 'Antrean sudah selesai, tidak dapat dibatalkan');
            }

            $antrean->update(['status' => 'dibatalkan']);

            return $antrean;
        });
    }
}

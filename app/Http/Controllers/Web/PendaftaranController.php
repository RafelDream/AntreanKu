<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\CekNikRequest;
use App\Http\Requests\RegistrasiPasienBaruRequest;
use App\Http\Requests\RegistrasiPasienLamaRequest;
use App\Models\Antrean;
use App\Models\Pasien;
use App\Services\AntreanService;
use Illuminate\Http\Request;

class PendaftaranController extends Controller
{
    public function __construct(private AntreanService $antreanService)
    {
    }

    /* ---------- Daftar (pasien baru) ---------- */

    public function formBaru()
    {
        return view('pendaftaran.baru');
    }

    public function simpanBaru(RegistrasiPasienBaruRequest $request)
    {
        [, $antrean] = $this->antreanService->daftarPasienBaru($request->validated());

        return redirect()
            ->route('antrean.tiket', $antrean->token)
            ->with('berhasil', 'Registrasi berhasil. Simpan Nomor Rekam Medis Anda.');
    }

    /* ---------- Masuk (pasien lama): via NIK ---------- */

    public function formMasuk()
    {
        return view('pendaftaran.masuk');
    }

    public function cekMasuk(CekNikRequest $request)
    {
        $pasien = $this->antreanService->cariByNik($request->nik);

        if (! $pasien) {
            return redirect()->route('masuk')
                ->withInput()
                ->withErrors(['nik_belum_terdaftar' => 'NIK belum terdaftar, silakan daftar sebagai pasien baru']);
        }

        return $this->mintaKonfirmasi($request, $pasien, 'masuk');
    }

    /* ---------- Masuk (pasien lama): "Lupa NIK?" via No. RM + tanggal lahir ---------- */

    public function formLupaNik()
    {
        return view('pendaftaran.lupa-nik');
    }

    public function cekLupaNik(RegistrasiPasienLamaRequest $request)
    {
        $pasien = $this->antreanService->cariPasienLama(
            trim($request->no_rekam_medis),
            $request->tanggal_lahir
        );

        if (! $pasien) {
            return redirect()->route('masuk.lupa')
                ->withInput()
                ->withErrors(['login' => 'Nomor Rekam Medis atau Tanggal Lahir tidak cocok']);
        }

        return $this->mintaKonfirmasi($request, $pasien, 'masuk.lupa');
    }

    /* ---------- Konfirmasi identitas (popup) -> terbitkan antrean ---------- */

    public function konfirmasiMasuk(Request $request)
    {
        $pasienId = $request->session()->pull('masuk_pasien_id');

        if (! $pasienId) {
            return redirect()->route('masuk')
                ->withErrors(['nik' => 'Data pasien tidak ditemukan, silakan ulangi dari awal']);
        }

        $antrean = $this->antreanService->terbitkanNomorAntrean($pasienId);

        $redirect = redirect()->route('antrean.tiket', $antrean->token);

        return $antrean->wasRecentlyCreated
            ? $redirect->with('berhasil', 'Berhasil masuk. Nomor antrean Anda sudah terbit.')
            : $redirect->with('info', 'Anda sudah memiliki nomor antrean hari ini.');
    }

    /* ---------- Tiket & status antrean ---------- */

    public function tiket(string $token)
    {
        $antrean = Antrean::with('pasien')->where('token', $token)->firstOrFail();

        return view('antrean.tiket', [
            'antrean'   => $antrean,
            'ringkasan' => $this->antreanService->ringkasan($antrean),
        ]);
    }

    public function status(string $token)
    {
        $antrean = Antrean::where('token', $token)->firstOrFail();

        return response()->json($this->antreanService->ringkasan($antrean));
    }

    /**
     * ID pasien disimpan di server (session), bukan dikirim ulang dari browser,
     * sehingga tombol konfirmasi tidak bisa dipakai untuk pasien lain.
     */
    private function mintaKonfirmasi(Request $request, Pasien $pasien, string $kembaliKe)
    {
        $request->session()->put('masuk_pasien_id', $pasien->id);

        return redirect()->route($kembaliKe)->with('konfirmasi', [
            'nik_tersamar' => $this->antreanService->nikTersamar($pasien),
            'nama_lengkap' => $pasien->nama_lengkap,
        ]);
    }
}

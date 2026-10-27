<?php

namespace App\Console\Commands;

use App\Models\Antrean;
use Illuminate\Console\Command;

class SimulasiPanggilAntrean extends Command
{
    protected $signature = 'antrean:panggil';
    protected $description = 'Simulasi admin memanggil nomor antrean berikutnya (dipakai sebelum Dashboard Admin Sprint 4 jadi)';

    public function handle(): int
    {
        $hariIni = now()->toDateString();

        $next = Antrean::where('tanggal_antrean', $hariIni)
            ->where('status', 'menunggu')
            ->orderBy('nomor_antrean')
            ->first();

        if (! $next) {
            $this->warn('Tidak ada antrean menunggu hari ini.');
            return self::SUCCESS;
        }

        $next->update(['status' => 'dipanggil']);
        $this->info("Nomor antrean {$next->nomor_antrean} dipanggil.");

        return self::SUCCESS;
    }
}
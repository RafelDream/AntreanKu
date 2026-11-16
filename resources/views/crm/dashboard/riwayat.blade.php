@extends('layouts.crm')

@section('title', 'Riwayat antrean')

@section('content')
    <div class="border-t-4 border-ink pt-5 pb-6">
        <h1 class="text-2xl font-bold tracking-tight">Riwayat antrean</h1>
        <p class="mt-1 text-sm text-muted">Pasien yang sudah selesai dilayani, dibatalkan, atau hangus.</p>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-line">
        <div class="flex flex-wrap gap-3">
            <input type="date" aria-label="Tanggal" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="px-3 py-1.5 rounded border border-line text-sm">
            <select aria-label="Status" class="px-3 py-1.5 rounded border border-line text-sm">
                <option value="">Semua status</option>
                <option value="selesai">Selesai</option>
                <option value="batal">Batal</option>
                <option value="hangus">Hangus</option>
            </select>
        </div>
        <input type="search" aria-label="Cari pasien" placeholder="Cari nama atau No. RM"
               class="w-full sm:w-64 px-3 py-1.5 rounded border border-line text-sm placeholder:text-muted/70">
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[600px] text-left text-sm">
            <thead>
                <tr class="text-xs text-muted">
                    <th class="py-3 pr-4 w-24 font-medium">No. antrean</th>
                    <th class="py-3 pr-4 font-medium">Pasien</th>
                    <th class="py-3 pr-4 w-44 font-medium">Status</th>
                    <th class="py-3 w-28 text-right font-medium">Waktu</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-line border-t border-line">
                <tr>
                    <td class="py-4 pr-4 text-lg font-bold tabular-nums">A-01</td>
                    <td class="py-4 pr-4"><span class="block font-semibold">Diana Putri</span><span class="text-xs text-muted tabular-nums">RM0045</span></td>
                    <td class="py-4 pr-4"><span class="block font-medium text-brand-deep">Selesai</span><span class="text-xs text-muted">Dipanggil 1 kali</span></td>
                    <td class="py-4 text-right tabular-nums">08:14 WIB</td>
                </tr>
                <tr>
                    <td class="py-4 pr-4 text-lg font-bold tabular-nums">A-02</td>
                    <td class="py-4 pr-4"><span class="block font-semibold">Ahmad Fauzi</span><span class="text-xs text-muted tabular-nums">RM0072</span></td>
                    <td class="py-4 pr-4"><span class="block font-medium text-danger">Batal</span><span class="text-xs text-muted">Belum dipanggil</span></td>
                    <td class="py-4 text-right tabular-nums">08:45 WIB</td>
                </tr>
                <tr>
                    <td class="py-4 pr-4 text-lg font-bold tabular-nums">A-04</td>
                    <td class="py-4 pr-4"><span class="block font-semibold">Supriyanto</span><span class="text-xs text-muted tabular-nums">RM0021</span></td>
                    <td class="py-4 pr-4"><span class="block font-medium text-muted">Hangus</span><span class="text-xs text-muted">Dipanggil 3 kali</span></td>
                    <td class="py-4 text-right tabular-nums">09:10 WIB</td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
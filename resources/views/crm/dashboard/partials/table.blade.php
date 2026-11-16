<div class="overflow-x-auto">
    <table class="w-full min-w-[640px] text-left text-sm">
        <thead>
            <tr class="text-xs text-muted">
                <th class="py-3 pr-4 w-24 font-medium">No. antrean</th>
                <th class="py-3 pr-4 font-medium">Pasien</th>
                <th class="py-3 pr-4 w-44 font-medium">Status</th>
                <th class="py-3 w-64 text-right font-medium">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-line border-t border-line">

            <!-- Dipanggil -->
            <tr x-show="currentFilter === 'semua' || currentFilter === 'menunggu'">
                <td class="py-4 pr-4 text-lg font-bold tabular-nums shadow-[inset_3px_0_0_#08CE56] pl-3">A-07</td>
                <td class="py-4 pr-4">
                    <span class="block font-semibold text-ink">Siti Aminah</span>
                    <span class="text-xs text-muted tabular-nums">RM0012</span>
                </td>
                <td class="py-4 pr-4">
                    <span class="block font-semibold text-ink">Dipanggil</span>
                    <span class="text-xs text-muted">Panggilan ke-1</span>
                </td>
                <!-- Bagian toggle ditaruh di td ini -->
                <td class="py-4 text-right">
                    <div x-data="{ status: null }"
                        class="relative inline-flex p-0.5 rounded-lg bg-wash border border-line w-44">

                        <!-- Blok Background Bergeser (Sekarang membawa warna) -->
                        <div class="absolute top-0.5 bottom-0.5 left-0.5 w-[calc(50%-4px)] shadow-[0_1px_2px_rgba(0,0,0,0.15)] rounded-md transition-all duration-300 ease-out"
                            x-show="status !== null" :class="{
                 'translate-x-0 bg-[#07b54c]': status === 'hadir',
                 'translate-x-[104%] bg-red-500': status === 'belum'
             }">
                        </div>

                        <!-- Opsi Hadir -->
                        <button type="button" @click="status = 'hadir'"
                            class="relative z-10 w-1/2 py-1.5 text-xs font-semibold transition-colors duration-200"
                            :class="status === 'hadir' ? 'text-white' : 'text-muted hover:text-ink'">
                            Hadir
                        </button>

                        <!-- Opsi Belum Hadir -->
                        <button type="button" @click="status = 'belum'"
                            class="relative z-10 w-1/2 py-1.5 text-xs font-semibold transition-colors duration-200"
                            :class="status === 'belum' ? 'text-white' : 'text-muted hover:text-ink'">
                            Belum
                        </button>

                    </div>
                </td>
            </tr>

            <!-- Menunggu -->
            <tr x-show="currentFilter === 'semua' || currentFilter === 'menunggu'">
                <td class="py-4 pr-4 text-lg font-bold tabular-nums pl-3">A-08</td>
                <td class="py-4 pr-4">
                    <span class="block font-semibold text-ink">Budi Santoso</span>
                    <span class="text-xs text-muted tabular-nums">RM0003</span>
                </td>
                <td class="py-4 pr-4">
                    <span class="block font-medium text-muted">Menunggu</span>
                    <span class="text-xs text-muted">Belum dipanggil</span>
                </td>
                <td class="py-4 text-right">
                    <div x-data="{ action: null }"
                        class="relative inline-flex p-0.5 rounded-lg bg-wash border border-line w-44">

                        <!-- Blok Background Bergeser (Sekarang membawa warna) -->
                        <div class="absolute top-0.5 bottom-0.5 left-0.5 w-[calc(50%-4px)] shadow-[0_1px_2px_rgba(0,0,0,0.15)] rounded-md transition-all duration-300 ease-out"
                            x-show="action !== null" :class="{
                 'translate-x-0 bg-ink': action === 'panggil',
                 'translate-x-[104%] bg-red-500': action === 'batal'
             }">
                        </div>

                        <!-- Opsi Panggil -->
                        <button type="button" @click="action = 'panggil'"
                            class="relative z-10 w-1/2 py-1.5 text-xs font-semibold transition-colors duration-200"
                            :class="action === 'panggil' ? 'text-white' : 'text-muted hover:text-ink'">
                            Panggil
                        </button>

                        <!-- Opsi Batal -->
                        <button type="button" @click="action = 'batal'"
                            class="relative z-10 w-1/2 py-1.5 text-xs font-semibold transition-colors duration-200"
                            :class="action === 'batal' ? 'text-white' : 'text-muted hover:text-ink'">
                            Batal
                        </button>

                    </div>
                </td>
            </tr>

            <!-- Sisipan -->
<tr x-show="currentFilter === 'semua' || currentFilter === 'sisipan'">
    <td class="py-4 pr-4 text-lg font-bold tabular-nums pl-3">A-03</td>
    <td class="py-4 pr-4">
        <span class="block font-semibold">Agus Setiawan</span>
        <span class="text-xs text-muted tabular-nums">RM0089</span>
    </td>
    <td class="py-4 pr-4">
        <span class="block font-semibold text-warn">Sisipan</span>
        <span class="text-xs text-muted">Panggilan ke-2 dari 3</span>
    </td>
    <td class="py-4 text-right">
        <div class="inline-flex items-center p-0.5 rounded-lg bg-wash border border-line">
            
            <!-- Tambahkan x-data dan @click untuk mengatur state isSpinning -->
            <button type="button" 
                x-data="{ isSpinning: false }" 
                @click="isSpinning = true; setTimeout(() => isSpinning = false, 1000)"
                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md bg-white text-ink text-xs font-semibold shadow-[0_1px_2px_rgba(0,0,0,0.06)] border border-line/60 hover:bg-ink hover:text-white hover:border-ink transition-all">
                
                <!-- Tambahkan kondisi :class animate-spin ke ikon SVG -->
                <svg class="w-3 h-3 stroke-[2]" 
                     :class="isSpinning ? 'animate-spin' : ''" 
                     viewBox="0 0 16 16" fill="none" stroke="currentColor">
                    <path d="M2.5 8a5.5 5.5 0 0 1 9.3-3.9L13.5 6M13.5 2.5V6H10M13.5 8a5.5 5.5 0 0 1-9.3 3.9L2.5 10M2.5 13.5V10H6" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Panggil ulang
                
            </button>
            
        </div>
    </td>
</tr>

            <!-- Selesai -->
            <tr class="text-muted" x-show="currentFilter === 'semua' || currentFilter === 'selesai'">
                <td class="py-4 pr-4 text-lg font-bold tabular-nums pl-3">A-01</td>
                <td class="py-4 pr-4"><span class="block font-semibold text-ink">Diana Putri</span><span
                        class="text-xs tabular-nums">RM0045</span></td>
                <td class="py-4 pr-4"><span class="block font-medium text-brand-deep">Selesai</span></td>
                <td class="py-4 text-right">&ndash;</td>
            </tr>

        </tbody>
    </table>
</div>
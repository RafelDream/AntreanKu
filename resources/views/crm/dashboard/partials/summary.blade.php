@php
    $tabs = [
        'semua'    => ['Semua', 20],
        'menunggu' => ['Menunggu', 12],
        'sisipan'  => ['Sisipan', 2],
        'selesai'  => ['Selesai / batal', 6],
    ];
@endphp

<div class="flex flex-wrap items-end justify-between gap-x-8 gap-y-3 border-b border-line">
    <div class="flex gap-6">
        @foreach($tabs as $key => [$label, $count])
            <button @click="currentFilter = '{{ $key }}'"
                    :class="currentFilter === '{{ $key }}' ? 'border-ink text-ink' : 'border-transparent text-muted hover:text-ink'"
                    class="-mb-px pb-3 text-sm font-semibold border-b-2 whitespace-nowrap">
                {{ $label }} <span class="ml-1 font-normal tabular-nums">{{ $count }}</span>
            </button>
        @endforeach
    </div>
    <input type="search" aria-label="Cari pasien" placeholder="Cari nama atau No. RM"
           class="mb-2 w-full sm:w-60 px-3 py-1.5 rounded border border-line text-sm placeholder:text-muted/70">
</div>
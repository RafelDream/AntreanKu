@php
    $onDashboard = request()->routeIs('crm.dashboard.index');
    $onRiwayat   = request()->routeIs('crm.riwayat.index');
@endphp

<aside class="bg-white border-r border-line h-screen shrink-0 z-20 overflow-hidden transition-[width] duration-200 motion-reduce:transition-none"
       :class="isSidebarOpen ? 'w-60' : 'w-0 border-r-0'">
    <div class="w-60 h-full flex flex-col">

        <div class="h-14 px-5 flex items-center gap-2.5 border-b border-line shrink-0">
            <span class="w-6 h-6 rounded-md bg-brand flex items-center justify-center font-display text-sm font-bold" aria-hidden="true">A</span>
            <span class="font-display text-lg font-bold tracking-tight">Antreanku</span>
        </div>

        <nav class="flex-1 p-3 space-y-0.5" aria-label="Menu utama">
            <a href="{{ route('crm.dashboard.index') }}" @if($onDashboard) aria-current="page" @endif
               @class(['flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition-colors',
                       'bg-brand/15 text-ink' => $onDashboard,
                       'text-muted hover:bg-paper hover:text-ink' => !$onDashboard])>
                <svg class="w-[18px] h-[18px] {{ $onDashboard ? 'text-brand-deep' : '' }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/></svg>
                Dashboard
            </a>
            <a href="{{ route('crm.riwayat.index') }}" @if($onRiwayat) aria-current="page" @endif
               @class(['flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition-colors',
                       'bg-brand/15 text-ink' => $onRiwayat,
                       'text-muted hover:bg-paper hover:text-ink' => !$onRiwayat])>
                <svg class="w-[18px] h-[18px] {{ $onRiwayat ? 'text-brand-deep' : '' }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Riwayat antrean
            </a>
        </nav>

        <div class="p-4 border-t border-line">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-paper border border-line flex items-center justify-center text-xs font-semibold">ST</div>
                <div class="min-w-0">
                    <p class="text-sm font-semibold leading-tight truncate">Staf Pendaftaran</p>
                    <p class="text-xs text-muted">Poli Umum</p>
                </div>
            </div>
            <button class="mt-3 text-sm font-medium text-muted hover:text-danger transition-colors">Keluar</button>
        </div>
    </div>
</aside>
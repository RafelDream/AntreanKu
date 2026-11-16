@php
    $links = [
        ['Dashboard', route('crm.dashboard.index'), request()->routeIs('crm.dashboard.index')],
        ['Riwayat antrean', route('crm.riwayat.index'), request()->routeIs('crm.riwayat.index')],
    ];
@endphp

<header class="border-b border-line">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 h-14 flex items-center gap-8">
        <span class="font-bold tracking-tight">Antreanku</span>

        <nav class="flex gap-6 h-full text-sm" aria-label="Menu utama">
            @foreach($links as [$label, $url, $active])
                <a href="{{ $url }}" @if($active) aria-current="page" @endif
                   @class(['h-full flex items-center border-b-2 -mb-px font-medium',
                           'border-ink text-ink' => $active,
                           'border-transparent text-muted hover:text-ink' => !$active])>{{ $label }}</a>
            @endforeach
        </nav>

        <div class="ml-auto flex items-center gap-5 text-sm text-muted">
            <time class="hidden md:block tabular-nums">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</time>
            <span class="hidden sm:block">{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('crm.logout') }}">
                @csrf
                <button type="submit" class="font-medium text-ink hover:text-danger">Keluar</button>
            </form>
        </div>
    </div>
</header>
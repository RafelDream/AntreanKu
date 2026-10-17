@extends('layouts.app')

@section('title', 'ANTREANKU — Antrean klinik online')
@section('shell', 'shell--solo')

@section('content')
    <div class="welcome">
        <header class="welcome__head">
            <h1 class="welcome__title">AntreanKu</h1>
            <p class="welcome__tagline">Solusi pendaftaran antrean klinik secara online yang cepat dan mudah</p>
        </header>

        {{-- Ekspor gambar dari Figma ke public/images/welcome.png --}}
        <div class="welcome__art">
            <img src="{{ asset('images/welcome.webp') }}" alt="" width="104" height="104"
                 onerror="this.style.visibility='hidden'">
        </div>

        <p class="welcome__prompt" id="pilih-status">Silakan pilih status pasien Anda:</p>

        <nav class="welcome__actions" aria-labelledby="pilih-status">
            <a href="{{ route('masuk') }}" class="btn btn--primary">Saya Pasien Lama</a>
            <a href="{{ route('daftar') }}" class="btn btn--outline">Saya Pasien Baru</a>
        </nav>

        @php($telepon = config('antreanku.telepon_klinik'))
        <p class="switch">
            Butuh bantuan?
            @if ($telepon)
                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $telepon) }}">Hubungi Klinik</a>
            @else
                <strong>Hubungi Klinik</strong>
            @endif
        </p>
    </div>
@endsection

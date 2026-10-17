@extends('layouts.app')

@section('title', 'Masuk — ANTREANKU')

@section('content')
    <header class="page-header page-header--low">
        <a href="{{ route('beranda') }}" class="back" aria-label="Kembali"
           onclick="if (history.length > 1) { history.back(); return false; }">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/></svg>
        </a>
        <h1 class="page-title">Masuk</h1>
    </header>

    {{-- NIK belum terdaftar: penanganan terpisah dari salah format --}}
    @error('nik_belum_terdaftar')
        <div class="alert alert--warn" role="alert">
            {{ $message }}. <a href="{{ route('daftar') }}">Daftar sekarang</a>
        </div>
    @enderror

    <form method="POST" action="{{ route('masuk.cek') }}" class="form" novalidate data-once>
        @csrf

        <x-field name="nik" label="NIK" placeholder="16 digit angka"
                 inputmode="numeric" maxlength="16" autocomplete="off" data-digits-only required />

        <a href="{{ route('masuk.lupa') }}" class="forgot">Lupa NIK?</a>

        <x-consent />

        <button type="submit" class="btn btn--primary">Ambil Nomor Antrean</button>
    </form>

    <p class="switch">Belum pernah mendaftar? <a href="{{ route('daftar') }}">Daftar lewat sini</a></p>

    @include('pendaftaran._konfirmasi', ['tutupUrl' => route('masuk')])
@endsection

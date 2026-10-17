@extends('layouts.app')

@section('title', 'Lupa NIK — ANTREANKU')

@section('content')
    <header class="page-header page-header--low">
        <a href="{{ route('masuk') }}" class="back" aria-label="Kembali"
           onclick="if (history.length > 1) { history.back(); return false; }">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/></svg>
        </a>
        <h1 class="page-title">Lupa NIK</h1>
    </header>

    <p class="intro">Masuk dengan Nomor Rekam Medis pada Kartu Kuning dan tanggal lahir Anda.</p>

    @error('login')
        <div class="alert alert--error" role="alert">{{ $message }}</div>
    @enderror

    <form method="POST" action="{{ route('masuk.lupa.cek') }}" class="form" novalidate data-once>
        @csrf

        <x-field name="no_rekam_medis" label="Nomor Rekam Medis" placeholder="Nomor pada Kartu Kuning"
                 autocomplete="off" autocapitalize="characters" required />

        <x-field name="tanggal_lahir" label="Tanggal lahir" type="date"
                 :max="now()->toDateString()" autocomplete="bday" required />

        <x-consent />

        <button type="submit" class="btn btn--primary">Ambil Nomor Antrean</button>
    </form>

    <p class="switch">Ingat NIK Anda? <a href="{{ route('masuk') }}">Masuk dengan NIK</a></p>

    @include('pendaftaran._konfirmasi', ['tutupUrl' => route('masuk.lupa')])
@endsection

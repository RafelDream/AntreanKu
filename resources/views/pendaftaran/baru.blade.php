@extends('layouts.app')

@section('title', 'Daftar — ANTREANKU')

@section('content')
    <header class="page-header">
        <a href="{{ route('beranda') }}" class="back" aria-label="Kembali"
           onclick="if (history.length > 1) { history.back(); return false; }">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/></svg>
        </a>
        <h1 class="page-title">Daftar</h1>
    </header>

    <form method="POST" action="{{ route('daftar.simpan') }}" class="form" novalidate data-once>
        @csrf

        <x-field name="nik" label="NIK" placeholder="16 digit angka"
                 inputmode="numeric" maxlength="16" autocomplete="off" data-digits-only required />

        <x-field name="nama_lengkap" label="Nama" placeholder="Nama lengkap"
                 autocomplete="name" required />

        <x-field name="tanggal_lahir" label="Tanggal lahir" type="date"
                 :max="now()->toDateString()" autocomplete="bday" required />

        <x-field name="alamat" label="Alamat" placeholder="alamat sesuai KTP"
                 autocomplete="street-address" />

        <x-field name="no_telepon" label="Nomor telepon" type="tel" placeholder="08xx-xxxx-xxxx"
                 inputmode="tel" autocomplete="tel" />

        <x-consent />

        <button type="submit" class="btn btn--primary">Ambil Nomor Antrean</button>
    </form>

    <p class="switch">Sudah pernah mendaftar? <a href="{{ route('masuk') }}">Masuk lewat sini</a></p>
@endsection

@extends('layouts.app')

@section('title', 'Login CRM — ANTREANKU')

{{-- Teks khusus untuk panel kiri CRM --}}
@section('panel_title', 'Kendali penuh operasional antrean klinik.')
@section('panel_text', 'Pantau giliran secara real-time, kelola pemanggilan pasien, dan pastikan kelancaran layanan dari dashboard ini.')

@section('content')
    <header class="page-header page-header--low mb-8" style="display: flex; flex-direction: column; align-items: center; text-align: center;">
        <h1 class="page-title text-center text-[#08CE56]">Login CRM</h1>
        <p class="text-center w-full text-gray-500 mt-2 text-sm">Portal manajemen antrean untuk staf klinik</p>
    </header>

    @error('login_failed')
        <div class="alert alert--error" role="alert">{{ $message }}</div>
    @enderror

    <form method="POST" action="{{ route('crm.login.store') }}" class="form" novalidate data-once>
        @csrf

        <x-field name="username" label="Username" placeholder="Masukkan username" required autocomplete="username" />
        
        <x-field name="password" label="Password" placeholder="Masukkan password" type="password" required />

        <button type="submit" class="btn btn--primary w-full mt-6">Masuk Dashboard</button>
    </form>
@endsection
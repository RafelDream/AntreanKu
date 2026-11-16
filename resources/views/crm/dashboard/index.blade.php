@extends('layouts.crm')

@section('title', 'Dashboard petugas')

@section('content')
    <div x-data="{ currentFilter: 'semua' }">
        @include('crm.dashboard.partials.active_call')
        @include('crm.dashboard.partials.summary')
        @include('crm.dashboard.partials.table')
    </div>
@endsection
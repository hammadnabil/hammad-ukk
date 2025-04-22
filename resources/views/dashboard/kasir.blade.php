@extends('layouts.app')

@section('title', 'Dashboard Kasir')

@section('content')
<h2>Dashboard Kasir</h2>
<p>Selamat datang, {{ auth()->user()->name }}!</p>

<div class="row">
    <div class="col-md-3">
        <a href="{{ route('kasir.index') }}" class="btn btn-warning w-100">Kelola Transaksi</a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('kasir.history') }}" class="btn btn-primary w-100">lihat riwayat Transaksi</a>
    </div>
</div>
@endsection

@section('scripts')
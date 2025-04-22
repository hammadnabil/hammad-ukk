@extends('layouts.app')

@section('title', 'Dashboard Manager')

@section('content')
<h2>Dashboard Manager</h2>
<p>Selamat datang, {{ auth()->user()->name }}!</p>

<div class="row">
    <div class="col-md-3">
        <a href="{{ route('manager.menu.index') }}" class="btn btn-warning w-100">Kelola menu</a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('manager.history') }}" class="btn btn-secondary w-100">Riwayat Transaksi</a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('manager.logs') }}" class="btn btn-primary w-100">Log aktivitas</a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('manager.report') }}" class="btn btn-danger w-100">Laporan Pendapatan</a>
    </div>
</div>
@endsection

@section('scripts')
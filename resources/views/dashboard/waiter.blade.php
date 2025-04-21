@extends('layouts.app')

@section('title', 'Dashboard waiter')

@section('content')
<h2>Dashboard waiter</h2>
<p>Selamat datang, {{ auth()->user()->name }}!</p>

<div class="row">
    <div class="col-md-3">
        <a href="{{ route('waiter.orders.index') }}" class="btn btn-warning w-100">lihat daftar pesanan</a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('waiter.orders.create') }}" class="btn btn-primary w-100">buat pesanan</a>
    </div>
</div>
@endsection

@section('scripts')
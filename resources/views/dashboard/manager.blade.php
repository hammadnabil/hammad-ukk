@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<h2>Dashboard Admin</h2>
<p>Selamat datang, {{ auth()->user()->name }}!</p>

<div class="row">
    <div class="col-md-3">
        <a href="{{ route('manager.menu.index') }}" class="btn btn-warning w-100">Kelola menu</a>
    </div>
</div>
@endsection

@section('scripts')
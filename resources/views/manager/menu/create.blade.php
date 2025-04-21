@extends('layouts.app')

@section('content')
<h2>Tambah Menu</h2>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<form method="POST" action="{{ route('manager.menu.store') }}">
    @csrf
    <div class="mb-3">
        <label>Nama Menu</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Harga</label>
        <input type="number" name="price" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Deskripsi</label>
        <textarea name="description" class="form-control"></textarea>
    </div>
    <button type="submit" class="btn btn-success">Simpan</button>
    <a href="{{ route('manager.menu.index') }}" class="btn btn-secondary">Kembali</a>
</form>
@endsection

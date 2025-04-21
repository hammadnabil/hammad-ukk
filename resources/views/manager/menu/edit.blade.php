@extends('layouts.app')

@section('content')
<h2>Edit Menu</h2>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<form method="POST" action="{{ route('manager.menu.update', $menu->id) }}">
    @csrf
    @method('PUT')
    
    <div class="mb-3">
        <label>Nama Menu</label>
        <input type="text" name="name" class="form-control" value="{{ $menu->name }}" required>
    </div>
    <div class="mb-3">
        <label>Harga</label>
        <input type="number" name="price" class="form-control" value="{{ $menu->price }}" required>
    </div>
    <div class="mb-3">
        <label>Deskripsi</label>
        <textarea name="description" class="form-control">{{ $menu->description }}</textarea>
    </div>
    
    <button type="submit" class="btn btn-success">Simpan</button>
    <a href="{{ route('manager.menu.index') }}" class="btn btn-secondary">Kembali</a>
</form>
@endsection


@extends('layouts.app')

@section('title','list menu')

@section('content')

<h2>list menu</h2>
<a href="{{route('manager.menu.create')}}" class="btn btn-primary mb-3">Tambah menu</a>
<a href="{{ route('dashboard') }}" class="btn btn-secondary mb-3">Kembali</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nama</th>
            <th>Harga</th>
            <th>Deskripsi</th>
            <th class="center">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($menus as $menu)
            <tr>
                <td>{{$menu->name}}</td>
                <td>Rp:{{$menu->price}}</td>
                <td>{{$menu->description}}</td>
                <td>
                    <a href="{{ route('manager.menu.edit', $menu->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('manager.menu.destroy', $menu->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>

    @endsection
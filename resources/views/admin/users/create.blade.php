@extends('layouts.app')

@section('title', 'tambah user')

@section('content')
<h2>Tambah user</h2>


@if(session('success'))
    <div class="alert alert-success">
        {{session('success')}}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($error->all() as $error)
            <li>{{$error}}</li>
            @endforeach
        </ul>
    </div>

@endif

<form method="POST" action="{{route('admin.users.store')}}">
    @csrf
    <div class="mb-3">
        <label for="">nama</label>
        <input type="text" name="name" class="form-control">
    </div>
    <div class="mb-3">
        <label for="">email</label>
        <input type="text" name="email" class="form-control" required>
        @error('email')
            <div class="alert alert-danger">{{$message}}</div>
        @enderror
    </div>
    <div class="mb-3">
        <label for="">password</label>
        <input type="password" name="password" class="form-control">
    </div>
    <div class="mb-3">
        <label for="">role</label>
        <select name="role_id" class="form-control" id="">
            @foreach ($roles as $role )
            <option value="{{$role->id}}">{{$role->name}}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btn btn-primary">simpan</button>
    <a href="{{ route('admin.users.index')}}" class="btn btn-secondary">kembali</a>
</form>
@endsection
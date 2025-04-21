@extends('layouts.app')

@section('title', 'edit user')

@section('content')
<h2>Edit user{{$user->name}}</h2>

<form method="POST" action="{{route('admin.users.update', $user->id)}}">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label for="">nama</label>
        <input type="text" name="name" class="form-control" value="{{$user->name}}" required>
    </div>

    <div class="mb-3">
        <label for="">email</label>
        <input type="email" name="email" class="form-control" value="{{$user->email}}" required>
    </div>

    <div class="mb-3">
        <label for="">Role</label>
        <select name="role_id" class="form-control">
            @foreach ( $roles as $role )
            <option value="{{$role->id}}" {{$user->role_id == $role->id ? 'selected' : ''}}>
                {{$role->name}}
            </option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btn btn-primary mb-3">update</button>
    
</form>
<a href="{{ route('admin.users.index')}}" class="btn btn-secondary"> batal</a>



@endsection
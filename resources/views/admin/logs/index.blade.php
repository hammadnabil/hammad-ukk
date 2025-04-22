@extends('layouts.app')

@section('title', 'Log Aktivitas Pegawai')

@section('content')
<h2>Log Aktivitas Pegawai</h2>

<form method="GET" class="mb-3">
    <div class="row">
        <div class="col-md-4">
            <select name="user_id" class="form-control">
                <option value="">Pilih Pegawai</option>
                @foreach(\App\Models\User::all() as $user)
                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="date" name="date" id="dateInput" class="form-control" value="{{ request('date') }}">
            </div>
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('admin.logs.index') }}" class="btn btn-secondary">Reset</a>
            <a href="{{ route('dashboard')}}" class="btn btn-danger">Kembali</a>
        </div>
    </div>
</form>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Waktu</th>
            <th>Nama Pegawai</th>
            <th>Aksi</th>
            <th>IP Address</th>
            <th>User Agent</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($logs as $log)
        <tr>
            <td>{{ $log->created_at }}</td>
            <td>{{ $log->user->name }}</td>
            <td>{{ $log->action }}</td>
            <td>{{ $log->ip_address }}</td>
            <td>{{ $log->user_agent }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="d-flex justify-content-center mt-4">
    {{ $logs->links() }}
</div>

    
@endsection

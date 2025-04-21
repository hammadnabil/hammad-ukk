        @extends('layouts.app')

        @section('title', 'Dashboard Admin')

        @section('content')
        <h2>Dashboard Admin</h2>
        <p>Selamat datang, {{ auth()->user()->name }}!</p>

        <div class="row">
            <div class="col-md-4">
                <a href="{{ route('admin.users.index') }}" class="btn btn-warning w-100">Kelola User</a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('admin.logs.index') }}" class="btn btn-secondary w-100">Log Aktivitas</a>
            </div>
        </div>
        @endsection

        @section('scripts')
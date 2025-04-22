@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Riwayat Transaksi</h2>
    <a href="{{ route('dashboard') }}" class="btn btn-warning mb-3">Kembali</a>

    <form method="GET" action="{{ route('manager.history') }}" class="row g-3 mb-4">
        <div class="col-md-4">
            <label>Pegawai:</label>
            <select name="cashier_id" class="form-select">
                <option value="">-- Semua Pegawai --</option>
                @foreach(\App\Models\User::where('role_id', 3)->get() as $cashier)
                    <option value="{{ $cashier->id }}" {{ request('cashier_id') == $cashier->id ? 'selected' : '' }}>
                        {{ $cashier->name }}
                    </option>
                @endforeach
            </select>
        </div>
    
        <div class="col-md-4">
            <label>Tanggal:</label>
            <input type="date" name="date" class="form-control" value="{{ request('date') }}">
        </div>
    
        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-primary me-2">Filter</button>
            <a href="{{ route('manager.history') }}" class="btn btn-secondary">Reset</a>
        </div>

    </form>
    
    

    <table class="table table-bordered">
        <thead >
            <tr>
                <th>Kode Transaksi</th>
                <th>Kode Pesanan</th>
                <th>Total Harga</th>
                <th>Uang Tunai</th>
                <th>Waktu Pembayaran</th>
                <th>Pegawai</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
                <tr>
                    <td>{{$transaction->transaction_code}}</td>
                    <td>{{ $transaction->order->order_code }}</td>
                    <td>Rp{{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                    <td>Rp{{ number_format($transaction->cash, 0, ',', '.') }}</td>
                    <td>{{ $transaction->paid_at->format('d-m-Y H:i') }}</td> 
                    <td>{{ $transaction->cashier->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</form>
</div>
@endsection

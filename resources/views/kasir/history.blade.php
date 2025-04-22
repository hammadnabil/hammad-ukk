@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Riwayat Transaksi</h2>
    <a href="{{ route('dashboard') }}" class="btn btn-warning mb-3">Kembali</a>

    <table class="table table-bordered">
        <thead >
            <tr>
                <th>Kode Pesanan</th>
                <th>Total Harga</th>
                <th>Uang Tunai</th>
                <th>Kembalian</th>
                <th>Waktu Pembayaran</th>
                <th>Pegawai</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->order->order_code }}</td>
                    <td>Rp{{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                    <td>Rp{{ number_format($transaction->cash, 0, ',', '.') }}</td>
                    <td>Rp{{ number_format($transaction->change, 0, ',', '.') }}</td>
                    <td>{{ $transaction->paid_at->format('d-m-Y H:i') }}</td> 
                    <td>{{ $transaction->cashier->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

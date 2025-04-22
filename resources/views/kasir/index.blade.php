@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Daftar Pesanan Belum Dibayar</h2>
    <a href="{{ route('dashboard') }}" class="btn btn-warning mb-3">Kembali</a>

    <table class="table table-bordered">
        <thead >
            <tr>
                <th>#</th>
                <th>Kode Pesanan</th>
                <th>Total Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $order)
                @php
                    $items = json_decode($order->items, true);
                    $totalPrice = 0;

                    if (is_array($items)) {
                        foreach ($items as $item) {
                            $menu = \App\Models\Menus::find($item['id'] ?? 0);
                            $menuPrice = $menu ? $menu->price : 0;
                            $quantity = $item['quantity'] ?? 0;
                            $totalPrice += $menuPrice * $quantity;
                        }
                    }
                @endphp

                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $order->order_code }}</td>
                    <td>Rp{{ number_format($totalPrice, 0, ',', '.') }}</td>
                    <td>
                        <a href="{{ route('kasir.payment', $order->id) }}" class="btn btn-primary btn-sm">Bayar</a>
                    </td>
                </tr>
                @empty
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Pembayaran Pesanan #{{ $order->order_code }}</h2>

    @php
        $items = json_decode($order->items, true);
        $totalPrice = 0;

        foreach ($items as $item) {
            $menu = \App\Models\Menus::find($item['id']);
            if ($menu) {
                $totalPrice += $menu->price * $item['quantity'];
            }
        }
    @endphp

    <p>Total Harga: <strong>Rp{{ number_format($totalPrice, 0, ',', '.') }}</strong></p>

    <form action="{{ route('kasir.processPayment', $order->id) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="cash">Uang Tunai:</label>
            <input type="number" id="cash" name="cash" class="form-control" min="{{ $totalPrice }}" required>
        </div>

        <button type="submit" class="btn btn-success">Bayar</button>
    </form>
</div>
@endsection

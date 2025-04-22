@extends('layouts.app')

@section('title', 'list pesanan')

@section('content')

<h2>list menu</h2>
<a href="{{route('waiter.orders.create')}}" class="btn btn-primary mb-3">buat pesanan</a>
<a href="{{ route('dashboard') }}" class="btn btn-secondary mb-3">Kembali</a>

<table class="table table-bordered">
    <thead class=>
        <tr>
            <th>#</th>
            <th>Kode Pesanan</th>
            <th>Items</th>
            <th>Total Harga</th>
            <th>Status</th>
            <th>Waktu</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($orders as $order )
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$order->order_code}}</td>
                <td>
                    @foreach ($order->decoded_items as $item )
                        <div>
                            <span>{{$item['name']}}</span>
                            <small>x {{$item['quantity']}}</small>
                        </div>
                    @endforeach
                </td>
                <td>Rp{{ number_format($order->total_price, 0, ',', '.') }}</td>
                <td>
                    <span>{{ ucfirst($order->status) }}</span>
                </td>
                <td>{{ $order->created_at->format('d-m-Y H:i') }}</td>
                <td>
                    <a href="{{ route('waiter.orders.edit', $order->id) }}" class="btn btn-sm btn-outline-warning">edit</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>



@endsection
@extends('layouts.app')
@section('title', 'Detail Pesanan ' . $order->code)
@section('content')
<h1>Detail Pesanan {{ $order->code }}</h1>

@if(session('error'))
    <div class="card" style="margin-bottom:14px;border-color:#dc2626;color:#dc2626">
        {{ session('error') }}
    </div>
@endif

@if(session('success'))
    <div class="card" style="margin-bottom:14px;border-color:#16a34a;color:#16a34a">
        {{ session('success') }}
    </div>
@endif

<div class="card" style="margin-bottom:18px">
    <p>Status: <strong>{{ strtoupper($order->status) }}</strong></p>
    <p>Tanggal Order: {{ $order->ordered_at->format('d-m-Y H:i') }}</p>
    <p>Pelanggan: {{ $order->user?->name ?? '-' }} ({{ $order->user?->email ?? '-' }})</p>
    <p>Penerima: {{ $order->recipient_name }} | {{ $order->phone }}</p>
    <p>Alamat: {{ $order->address }}</p>
    <p>Pengiriman: {{ $order->shipping_courier }} {{ $order->shipping_service }} - {{ $order->shipping_eta }}</p>
    @if($order->validated_at)
        <p>Divalidasi oleh: {{ $order->validator?->name ?? '-' }} pada {{ $order->validated_at->format('d-m-Y H:i') }}</p>
    @endif

    <div style="margin-top:14px;display:flex;gap:10px;flex-wrap:wrap">
        @if($order->status === 'pending')
            <form method="POST" action="{{ route('admin.orders.validate', $order) }}">
                @csrf
                @method('PATCH')
                <button class="btn" type="submit">Validasi Pesanan</button>
            </form>
        @endif

        @if($order->status === 'validated')
            <form method="POST" action="{{ route('admin.orders.status', $order) }}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="processed">
                <button class="btn" type="submit">Ubah ke Processed</button>
            </form>
        @endif

        @if($order->status === 'processed')
            <form method="POST" action="{{ route('admin.orders.status', $order) }}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="shipped">
                <button class="btn" type="submit">Ubah ke Shipped</button>
            </form>
        @endif

        @if($order->status === 'shipped')
            <form method="POST" action="{{ route('admin.orders.status', $order) }}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="completed">
                <button class="btn" type="submit">Ubah ke Completed</button>
            </form>
        @endif

        @if(! in_array($order->status, ['cancelled', 'completed'], true))
            <form method="POST" action="{{ route('admin.orders.cancel', $order) }}">
                @csrf
                @method('PATCH')
                <button type="submit">Batalkan Pesanan</button>
            </form>
        @endif
    </div>
</div>

<div class="table-scroll">
    <table>
        <thead>
            <tr>
                <th>Produk</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td>Rp{{ number_format($item->price, 2, ',', '.') }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rp{{ number_format($item->subtotal, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="card" style="margin-top:16px">
    <p>Subtotal: Rp{{ number_format($order->subtotal, 2, ',', '.') }}</p>
    <p>Ongkir: Rp{{ number_format($order->shipping_cost, 2, ',', '.') }}</p>
    <h3>Total: Rp{{ number_format($order->grand_total, 2, ',', '.') }}</h3>
</div>
@endsection
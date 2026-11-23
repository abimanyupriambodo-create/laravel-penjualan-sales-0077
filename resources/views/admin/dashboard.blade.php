@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('content')
<h1>Dashboard Admin</h1>

<div class="card" style="margin-bottom:18px">
    <p>Total Produk: <strong>{{ $stats['products_count'] }}</strong> (Aktif: {{ $stats['active_products_count'] }}, Stok Menipis &le; 5: <strong>{{ $stats['low_stock_count'] }}</strong>)</p>
    <p>Pesanan Pending: <strong>{{ $stats['pending_orders_count'] }}</strong> | Pesanan Tervalidasi: <strong>{{ $stats['validated_orders_count'] }}</strong></p>
    <h3>Pendapatan Tervalidasi: Rp{{ number_format($stats['total_revenue'], 2, ',', '.') }}</h3>
</div>

<div class="card" style="margin-bottom:18px">
    <h3>Pesanan Terbaru</h3>
    <div class="table-scroll">
        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Pelanggan</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentOrders as $order)
                    <tr>
                        <td>{{ $order->code }}</td>
                        <td>{{ $order->user?->name ?? '-' }}</td>
                        <td>Rp{{ number_format($order->grand_total, 2, ',', '.') }}</td>
                        <td>{{ strtoupper($order->status) }}</td>
                        <td><a href="{{ route('admin.orders.show', $order) }}">Detail</a></td>
                    </tr>
                @empty
                    <tr><td colspan="5">Belum ada pesanan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <h3>Mutasi Stok Terbaru</h3>
    <div class="table-scroll">
        <table>
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Produk</th>
                    <th>Tipe</th>
                    <th>Jumlah</th>
                    <th>Referensi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentMovements as $movement)
                    <tr>
                        <td>{{ $movement->movement_at?->format('d-m-Y H:i') }}</td>
                        <td>{{ $movement->product?->name ?? '-' }}</td>
                        <td>{{ $movement->type }}</td>
                        <td>{{ $movement->quantity }}</td>
                        <td>{{ $movement->reference ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5">Belum ada mutasi stok.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
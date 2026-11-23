@extends('layouts.app')
@section('title', 'Daftar Pesanan')
@section('content')
<h1>Daftar Pesanan</h1>

<div class="card" style="margin-bottom:18px">
    <form method="GET" action="{{ route('admin.orders.index') }}">
        <label>Filter Status
            <select name="status" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="pending" @selected(request('status') === 'pending')>PENDING</option>
                <option value="validated" @selected(request('status') === 'validated')>VALIDATED</option>
            </select>
        </label>
    </form>
</div>

<div class="table-scroll">
    <table>
        <thead>
            <tr>
                <th>Kode</th>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>Total</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                <tr>
                    <td>{{ $order->code }}</td>
                    <td>{{ $order->ordered_at->format('d-m-Y H:i') }}</td>
                    <td>{{ $order->user?->name ?? '-' }}</td>
                    <td>Rp{{ number_format($order->grand_total, 2, ',', '.') }}</td>
                    <td>{{ strtoupper($order->status) }}</td>
                    <td>
                        <a href="{{ route('admin.orders.show', $order) }}">Detail</a>
                        @if($order->status === 'pending')
                            <form method="POST" action="{{ route('admin.orders.validate', $order) }}" style="display:inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit">Validasi</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6">Tidak ada data pesanan.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top:18px">{{ $orders->links() }}</div>
@endsection
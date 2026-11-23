
@extends('layouts.admin')

@section('title', 'Produk')

@section('content')

<div class="row" style="justify-content:space-between; align-items:center; margin-bottom:20px;">
    <div>
        <h1 style="margin:0;">Produk</h1>
        <p style="color:#888; margin-top:5px;">Kelola katalog dan persediaan produk.</p>
    </div>
    <a class="btn" href="{{ route('admin.products.create') }}">+ Tambah Produk</a>
</div>

{{-- Pencarian --}}
<div class="card" style="margin-bottom:20px;">
    <form method="GET" class="row" style="align-items:center;">
        <input
            class="input"
            style="max-width:320px;"
            name="q"
            value="{{ request('q') }}"
            placeholder="Cari nama produk / SKU..."
        >
        <button class="btn" type="submit">Cari</button>

        @if(request('q'))
            <a class="btn" href="{{ route('admin.products.index') }}">Reset</a>
        @endif
    </form>
</div>

{{-- Tabel Produk --}}
<div class="card">
    <div class="table-scroll">
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr>
                    <th style="padding:14px;">Foto</th>
                    <th style="padding:14px;">SKU</th>
                    <th style="padding:14px;">Produk</th>
                    <th style="padding:14px;">Kategori</th>
                    <th style="padding:14px;">Harga</th>
                    <th style="padding:14px;">Stok</th>
                    <th style="padding:14px;">Status</th>
                    <th style="padding:14px;">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($products as $product)
                    <tr>
                        {{-- Foto Produk --}}
                        <td style="padding:12px;">
                            @if($product->image)
                                <img
                                    src="{{ asset('storage/' . $product->image) }}"
                                    alt="{{ $product->name }}"
                                    loading="lazy"
                                    style="width:65px; height:65px; object-fit:cover; border-radius:10px; border:1px solid #ddd;"
                                >
                            @else
                                <div style="width:65px; height:65px; border-radius:10px; background:#f1f1f1; display:flex; align-items:center; justify-content:center; color:#999; font-size:11px;">
                                    No Image
                                </div>
                            @endif
                        </td>

                        <td style="padding:12px;">
                            {{ $product->sku }}
                        </td>

                        <td style="padding:12px; font-weight:600;">
                            {{ $product->name }}
                        </td>

                        <td style="padding:12px;">
                            {{ $product->category->name ?? '-' }}
                        </td>

                        <td style="padding:12px; white-space:nowrap;">
                            Rp{{ number_format($product->price, 0, ',', '.') }}
                        </td>

                        <td style="padding:12px;">
                            {{ $product->stock }}
                        </td>

                        <td style="padding:12px;">
                            @if($product->is_active)
                                <span style="background:#dcfce7; color:#15803d; padding:5px 10px; border-radius:20px; font-size:12px; font-weight:600;">
                                    Aktif
                                </span>
                            @else
                                <span style="background:#fee2e2; color:#b91c1c; padding:5px 10px; border-radius:20px; font-size:12px; font-weight:600;">
                                    Nonaktif
                                </span>
                            @endif
                        </td>

                        {{-- Tombol Aksi --}}
                        <td style="padding:12px;">
                            <div class="row" style="gap:6px; align-items:center;">
                                <a class="btn" href="{{ route('admin.products.edit', $product) }}">
                                    Edit
                                </a>

                                <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                                      onsubmit="return confirm('Yakin ingin menghapus atau menonaktifkan produk ini?')">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-danger" type="submit">
                                        Hapus/Nonaktif
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center; padding:30px; color:#888;">
                            Belum ada produk tersedia.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:20px;">
        {{ $products->links() }}
    </div>
</div>

@endsection

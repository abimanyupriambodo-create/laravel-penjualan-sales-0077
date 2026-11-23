@extends('layouts.admin')

@section('title', 'Tambah Produk')

@section('content')
<div class="row" style="justify-content:space-between; align-items:center;">
    <h1>Tambah Produk</h1>
    <a href="{{ route('admin.products.index') }}" class="btn">Kembali</a>
</div>

<div class="card">
    <form method="POST"
          enctype="multipart/form-data"
          action="{{ route('admin.products.store') }}">
        @csrf

        @include('admin.products._form')
    </form>
</div>
@endsection
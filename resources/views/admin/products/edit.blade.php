@extends('layouts.admin')

@section('title', 'Edit Produk')

@section('content')
<div class="row" style="justify-content:space-between; align-items:center;">
    <h1>Edit Produk</h1>
    <a href="{{ route('admin.products.index') }}" class="btn">Kembali</a>
</div>

<div class="card">
    <form method="POST"
          enctype="multipart/form-data"
          action="{{ route('admin.products.update', $product) }}">
        @csrf
        @method('PUT')

        @include('admin.products._form')
    </form>
</div>
@endsection
@extends('layouts.admin')
@section('title','Dashboard Admin')
@section('content')
<h1>Dashboard</h1>
<div class="grid">
    <div class="card"><strong>{{ $productCount }}</strong><br>Produk</div>
    <div class="card"><strong>{{ $lowStockCount }}</strong><br>Stok &lt;= 5</div>
</div>
@endsection
@extends('layouts.app')
@section('title', 'Products')
@section('content')
<div class="d-flex justify-content-between mb-3"><h4>Products</h4><a href="/products/new" class="btn btn-primary btn-sm">+ New Product</a></div>
<form method="GET" action="/products" class="row g-2 mb-3">
    <div class="col-md-4"><input type="text" name="q" class="form-control form-control-sm" placeholder="Search products..." value="{{ $q??'' }}"></div>
    <div class="col-md-2"><button class="btn btn-secondary btn-sm">Search</button></div>
</form>
<div class="card"><div class="card-body p-0">
    <table class="table table-hover mb-0">
        <thead class="table-light"><tr><th>SKU</th><th>Name</th><th>Category</th><th>Price</th><th>Stock</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
        @forelse($products as $p)
        <tr>
            <td><code>{{ $p->sku }}</code></td><td>{{ $p->name }}</td><td>{{ $p->category }}</td>
            <td>${{ number_format($p->price,2) }}</td>
            <td>@if($p->stock==0)<span class="badge bg-danger">Out of stock</span>@elseif($p->stock<10)<span class="badge bg-warning text-dark">{{ $p->stock }}</span>@else<span class="text-success">{{ $p->stock }}</span>@endif</td>
            <td>@if($p->active)<span class="badge bg-success">Active</span>@else<span class="badge bg-secondary">Inactive</span>@endif</td>
            <td>
                <a href="/products/{{ $p->id }}/edit" class="btn btn-outline-primary btn-sm">Edit</a>
                @if(Auth::user()->role=='admin')<a href="/products/{{ $p->id }}/delete" class="btn btn-outline-danger btn-sm" onclick="return confirm('Delete?')">Del</a>@endif
            </td>
        </tr>
        @empty<tr><td colspan="7" class="text-center text-muted">No products found.</td></tr>
        @endforelse
        </tbody>
    </table>
</div></div>
@if(isset($products)&&method_exists($products,'links'))<div class="mt-3">{{ $products->links() }}</div>@endif
@endsection

@extends('layouts.app')

@section('title', 'Products')

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h4>Products</h4>
    <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">+ New Product</a>
</div>

<form method="GET" action="{{ route('products.index') }}" class="row g-2 mb-3">
    <div class="col-md-4">
        <input type="text" name="q" class="form-control form-control-sm"
            placeholder="Search products..." value="{{ $q ?? '' }}">
    </div>
    <div class="col-md-2">
        <button class="btn btn-secondary btn-sm w-100">Search</button>
    </div>
    @if(!empty($q))
        <div class="col-md-2">
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm w-100">Clear</a>
        </div>
    @endif
</form>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>SKU</th><th>Name</th><th>Category</th>
                    <th>Price</th><th>Stock</th><th>Status</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td><code>{{ $product->sku }}</code></td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category ?? '—' }}</td>
                        <td>{{ $product->formatted_price }}</td>
                        <td>
                            @if($product->stock === 0)
                                <span class="badge bg-danger">Out of stock</span>
                            @elseif($product->stock < 10)
                                <span class="badge bg-warning text-dark">{{ $product->stock }}</span>
                            @else
                                <span class="text-success">{{ $product->stock }}</span>
                            @endif
                        </td>
                        <td>
                            @if($product->active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td class="d-flex gap-1">
                            <a href="{{ route('products.edit', $product->id) }}"
                                class="btn btn-outline-primary btn-sm">Edit</a>

                            @can('admin')
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                      onsubmit="return confirm('Delete this product?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm">Del</button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No products found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($products instanceof \Illuminate\Pagination\LengthAwarePaginator)
    <div class="mt-3">{{ $products->links() }}</div>
@endif

@endsection
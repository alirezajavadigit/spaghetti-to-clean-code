@extends('layouts.app')
@section('title', 'Edit Product')
@section('content')
<h4 class="mb-4">Edit Product</h4>
<form method="POST" action="/products/{{ $product->id }}/edit" enctype="multipart/form-data">
    @csrf
    <div class="card"><div class="card-body"><div class="row">
        <div class="col-md-6 mb-3"><label>SKU</label><input type="text" name="sku" class="form-control" value="{{ $product->sku }}" readonly></div>
        <div class="col-md-6 mb-3"><label>Name *</label><input type="text" name="name" class="form-control" value="{{ $product->name }}"></div>
        <div class="col-md-6 mb-3"><label>Price *</label><input type="text" name="price" class="form-control" value="{{ $product->price }}"></div>
        <div class="col-md-6 mb-3"><label>Stock</label><input type="number" name="stock" class="form-control" value="{{ $product->stock }}"></div>
        <div class="col-md-6 mb-3"><label>Category</label><input type="text" name="category" class="form-control" value="{{ $product->category }}"></div>
        <div class="col-md-6 mb-3"><label>Image</label>@if($product->image)<div class="mb-1"><img src="/uploads/products/{{ $product->image }}" height="60"></div>@endif<input type="file" name="image" class="form-control"></div>
        <div class="col-12 mb-3"><label>Description</label><textarea name="description" class="form-control" rows="3">{{ $product->description }}</textarea></div>
        <div class="col-12 mb-3"><div class="form-check"><input type="checkbox" name="active" id="active" class="form-check-input" {{ $product->active?'checked':'' }}><label for="active" class="form-check-label">Active</label></div></div>
    </div></div></div>
    <div class="mt-3"><button type="submit" class="btn btn-primary">Save Changes</button><a href="/products" class="btn btn-outline-secondary">Cancel</a></div>
</form>
@endsection

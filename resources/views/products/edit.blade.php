@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')

<h4 class="mb-4">Edit Product</h4>

<form method="POST" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">SKU</label>
                    <input type="text" class="form-control" value="{{ $product->sku }}" readonly>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Name *</label>
                    <input type="text" name="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $product->name) }}">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Price *</label>
                    <input type="number" step="0.01" name="price"
                        class="form-control @error('price') is-invalid @enderror"
                        value="{{ old('price', $product->price) }}">
                    @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Stock</label>
                    <input type="number" name="stock" class="form-control"
                        value="{{ old('stock', $product->stock) }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Category</label>
                    <input type="text" name="category" class="form-control"
                        value="{{ old('category', $product->category) }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Image</label>
                    @if($product->image)
                        <div class="mb-2">
                            <img src="/uploads/products/{{ $product->image }}"
                                height="60" class="rounded">
                        </div>
                    @endif
                    <input type="file" name="image"
                        class="form-control @error('image') is-invalid @enderror">
                    @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12 mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control"
                        rows="3">{{ old('description', $product->description) }}</textarea>
                </div>

                <div class="col-12 mb-3">
                    <div class="form-check">
                        <input type="checkbox" name="active" id="active"
                            class="form-check-input"
                            @checked(old('active', $product->active))>
                        <label for="active" class="form-check-label">Active</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3 d-flex gap-2">
        <button type="submit" class="btn btn-primary">Save Changes</button>
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </div>
</form>

@endsection
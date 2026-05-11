@extends('layouts.app')

@section('title', 'New Order')

@section('content')

<h4 class="mb-4">New Order</h4>

<form method="POST" action="{{ route('orders.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header">Order Details</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Customer *</label>
                        <select name="customer_id" class="form-select @error('customer_id') is-invalid @enderror">
                            <option value="">Select customer...</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" @selected(old('customer_id') == $customer->id)>
                                    {{ $customer->name }}{{ $customer->company ? ' (' . $customer->company . ')' : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Shipping Address *</label>
                        <textarea name="shipping_address" rows="2"
                            class="form-control @error('shipping_address') is-invalid @enderror">{{ old('shipping_address') }}</textarea>
                        @error('shipping_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Due Date</label>
                        <input type="date" name="due_date" class="form-control" value="{{ old('due_date') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Attachment</label>
                        <input type="file" name="attachment" class="form-control">
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">Products</div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr><th>Product</th><th>Price</th><th>Stock</th><th>Qty</th></tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                <tr>
                                    <td>
                                        {{ $product->name }}<br>
                                        <small class="text-muted">{{ $product->sku }}</small>
                                    </td>
                                    <td>{{ $product->formatted_price }}</td>
                                    <td>{{ $product->stock }}</td>
                                    <td>
                                        <input type="number" name="products[{{ $product->id }}]"
                                            class="form-control form-control-sm"
                                            style="width:80px" min="0" value="0">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <p class="text-muted small">Tax rate: 8.5%</p>
                    <button type="submit" class="btn btn-primary w-100">Create Order</button>
                    <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary w-100 mt-2">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection
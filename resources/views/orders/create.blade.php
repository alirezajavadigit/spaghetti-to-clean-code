@extends('layouts.app')
@section('title', 'New Order')
@section('content')
<h4 class="mb-4">New Order</h4>
<form method="POST" action="/orders/save" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header">Order Details</div>
                <div class="card-body">
                    <div class="mb-3"><label>Customer *</label>
                        <select name="customer_id" class="form-select"><option value="">Select customer...</option>
                        @foreach($customers as $c)<option value="{{ $c->id }}">{{ $c->name }} {{ $c->company?'('.$c->company.')':'' }}</option>@endforeach
                        </select></div>
                    <div class="mb-3"><label>Shipping Address *</label><textarea name="shipping_address" class="form-control" rows="2"></textarea></div>
                    <div class="mb-3"><label>Due Date</label><input type="date" name="due_date" class="form-control"></div>
                    <div class="mb-3"><label>Notes</label><textarea name="notes" class="form-control" rows="3"></textarea></div>
                    <div class="mb-3"><label>Attachment</label><input type="file" name="attachment" class="form-control"></div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">Products</div>
                <div class="card-body">
                    <table class="table"><thead><tr><th>Product</th><th>Price</th><th>Stock</th><th>Qty</th></tr></thead>
                    <tbody>
                    @foreach($products as $p)
                    <tr><td>{{ $p->name }}<br><small class="text-muted">{{ $p->sku }}</small></td><td>${{ $p->price }}</td><td>{{ $p->stock }}</td>
                    <td><input type="number" name="products[{{ $p->id }}]" class="form-control form-control-sm" style="width:80px" min="0" value="0"></td></tr>
                    @endforeach
                    </tbody></table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card"><div class="card-body">
                <p class="text-muted small">Tax rate: 8.5%</p>
                <button type="submit" class="btn btn-primary w-100">Create Order</button>
                <a href="/orders" class="btn btn-outline-secondary w-100 mt-2">Cancel</a>
            </div></div>
        </div>
    </div>
</form>
@endsection

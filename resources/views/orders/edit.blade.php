@extends('layouts.app')
@section('title', 'Edit Order')
@section('content')
<h4 class="mb-4">Edit Order {{ $order->order_number }}</h4>
<form method="POST" action="/orders/{{ $order->id }}/edit">
    @csrf
    <div class="card mb-3"><div class="card-body">
        <div class="mb-3"><label>Customer</label>
            <select name="customer_id" class="form-select">
            @foreach($customers as $c)<option value="{{ $c->id }}" {{ $c->id==$order->customer_id?'selected':'' }}>{{ $c->name }}</option>@endforeach
            </select></div>
        <div class="mb-3"><label>Shipping Address</label><textarea name="shipping_address" class="form-control" rows="2">{{ $order->shipping_address }}</textarea></div>
        <div class="mb-3"><label>Due Date</label><input type="date" name="due_date" class="form-control" value="{{ $order->due_date }}"></div>
        <div class="mb-3"><label>Notes</label><textarea name="notes" class="form-control" rows="3">{{ $order->notes }}</textarea></div>
    </div></div>
    <button type="submit" class="btn btn-primary">Save Changes</button>
    <a href="/orders/{{ $order->id }}" class="btn btn-outline-secondary">Cancel</a>
</form>
@endsection

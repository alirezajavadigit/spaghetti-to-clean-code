@extends('layouts.app')

@section('title', 'Edit Order ' . $order->order_number)

@section('content')

<h4 class="mb-4">Edit Order {{ $order->order_number }}</h4>

<form method="POST" action="{{ route('orders.update', $order->id) }}">
    @csrf
    @method('PUT')

    <div class="card mb-3">
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Customer</label>
                <select name="customer_id" class="form-select @error('customer_id') is-invalid @enderror">
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" @selected($customer->id === $order->customer_id)>
                            {{ $customer->name }}
                        </option>
                    @endforeach
                </select>
                @error('customer_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Shipping Address</label>
                <textarea name="shipping_address" class="form-control @error('shipping_address') is-invalid @enderror"
                    rows="2">{{ old('shipping_address', $order->shipping_address) }}</textarea>
                @error('shipping_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Due Date</label>
                <input type="date" name="due_date" class="form-control"
                    value="{{ old('due_date', $order->due_date?->format('Y-m-d')) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-control"
                    rows="3">{{ old('notes', $order->notes) }}</textarea>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Save Changes</button>
        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-outline-secondary">Cancel</a>
    </div>
</form>

@endsection
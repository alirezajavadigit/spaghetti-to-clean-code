@extends('layouts.app')

@section('title', 'Order ' . $order->order_number)

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h4>{{ $order->order_number }}</h4>
    <div class="d-flex gap-2">
        @if(!in_array($order->status, [\App\Models\Order::STATUS_DELIVERED, \App\Models\Order::STATUS_CANCELLED]))
            <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-outline-primary btn-sm">Edit</a>
        @endif

        @if(auth()->user()->role === 'admin')
            <form action="{{ route('orders.destroy', $order->id) }}" method="POST"
                  onsubmit="return confirm('Delete this order?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-outline-danger btn-sm">Delete</button>
            </form>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header">Order Items</div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Line Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td>
                                    {{ $item->product?->name ?? 'N/A' }}<br>
                                    <small class="text-muted">{{ $item->product?->sku }}</small>
                                </td>
                                <td>{{ $item->formatted_price }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>${{ number_format($item->line_total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="3" class="text-end">Subtotal</td>
                            <td>${{ number_format($order->subtotal, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end">Tax (8.5%)</td>
                            <td>${{ number_format($order->tax, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end fw-bold">Total</td>
                            <td class="fw-bold">${{ number_format($order->total, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        @if($order->notes)
            <div class="card mb-3">
                <div class="card-header">Notes</div>
                <div class="card-body">{{ $order->notes }}</div>
            </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header">Customer</div>
            <div class="card-body">
                <strong>{{ $customer->name }}</strong><br>
                {{ $customer->company }}<br>
                {{ $customer->email }}<br>
                {{ $customer->phone }}
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">Status</div>
            <div class="card-body">
                <div class="mb-2">
                    <span class="badge bg-{{ $order->status_color }} fs-6">
                        {{ $order->status_label }}
                    </span>
                </div>

                <form method="POST" action="{{ route('orders.status', $order->id) }}">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="form-select form-select-sm mb-2">
                        @foreach([
                            \App\Models\Order::STATUS_PENDING    => 'Pending',
                            \App\Models\Order::STATUS_PROCESSING => 'Processing',
                            \App\Models\Order::STATUS_SHIPPED    => 'Shipped',
                            \App\Models\Order::STATUS_DELIVERED  => 'Delivered',
                            \App\Models\Order::STATUS_CANCELLED  => 'Cancelled',
                        ] as $value => $label)
                            <option value="{{ $value }}" @selected($order->status === $value)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    <button class="btn btn-secondary btn-sm w-100">Update Status</button>
                </form>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">Details</div>
            <div class="card-body">
                <small class="text-muted">Created by</small><br>
                {{ $createdBy }}<br>
                <small class="text-muted">Date</small><br>
                {{ $order->created_at->format('M d, Y') }}<br>
                @if($order->due_date)
                    <small class="text-muted">Due</small><br>
                    {{ $order->due_date->format('M d, Y') }}<br>
                @endif
                <small class="text-muted">Ship to</small><br>
                {{ $order->shipping_address }}
            </div>
        </div>

        @if($order->attachment)
            <div class="card">
                <div class="card-body">
                    <a href="/uploads/{{ $order->attachment }}" target="_blank">
                        📎 {{ $order->attachment }}
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

@endsection
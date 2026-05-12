@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<h4 class="mb-4">Dashboard</h4>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="small">Total Revenue</div>
                <div class="fs-4">${{ number_format($total_revenue, 2) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="small">Total Orders</div>
                <div class="fs-4">{{ $total_orders }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <div class="small">Avg Order Value</div>
                <div class="fs-4">${{ number_format($avg_order_value, 2) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-secondary">
            <div class="card-body">
                <div class="small">Customers</div>
                <div class="fs-4">{{ $total_customers }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-2">
        <div class="card text-center">
            <div class="card-body py-2">
                <div class="small text-muted">Pending</div>
                <div class="fw-bold text-secondary">{{ $pending_count }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center">
            <div class="card-body py-2">
                <div class="small text-muted">Processing</div>
                <div class="fw-bold text-primary">{{ $processing_count }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center">
            <div class="card-body py-2">
                <div class="small text-muted">Shipped</div>
                <div class="fw-bold text-info">{{ $shipped_count }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center">
            <div class="card-body py-2">
                <div class="small text-muted">Delivered</div>
                <div class="fw-bold text-success">{{ $delivered_count }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center">
            <div class="card-body py-2">
                <div class="small text-muted">Cancelled</div>
                <div class="fw-bold text-danger">{{ $cancelled_count }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Recent Orders</div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr><th>Order #</th><th>Customer</th><th>Total</th><th>Status</th><th>Date</th></tr>
                    </thead>
                    <tbody>
                        @forelse($recent_orders as $order)
                            <tr>
                                <td>
                                    <a href="{{ route('orders.show', $order->id) }}">
                                        {{ $order->order_number }}
                                    </a>
                                </td>
                                <td>{{ $order->customer?->name ?? '—' }}</td>
                                <td>${{ number_format($order->total, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $order->status_color }}">
                                        {{ $order->status_label }}
                                    </span>
                                </td>
                                <td>{{ $order->created_at->format('M d') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No orders yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header text-danger">Low Stock</div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr><th>Product</th><th>Stock</th></tr>
                    </thead>
                    <tbody>
                        @forelse($low_stock_products as $product)
                            <tr>
                                <td>{{ $product->name }}</td>
                                <td>
                                    <span class="badge {{ $product->stock === 0 ? 'bg-danger' : 'bg-warning text-dark' }}">
                                        {{ $product->stock }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted">All products in stock.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
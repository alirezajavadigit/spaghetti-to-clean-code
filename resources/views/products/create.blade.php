@php use App\Models\Order; @endphp

@extends('layouts.app')

@section('title', 'Orders')

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h4>Orders</h4>
    <a href="{{ route('orders.create') }}" class="btn btn-primary btn-sm">+ New Order</a>
</div>

<form method="GET" action="{{ route('orders.search') }}" class="row g-2 mb-3">
    <div class="col-md-5">
        <input type="text" name="q" class="form-control form-control-sm"
            placeholder="Search orders or customers..." value="{{ $q ?? '' }}">
    </div>
    <div class="col-md-3">
        <select name="status" class="form-select form-select-sm">
            <option value="">All Statuses</option>
            @foreach([
                Order::STATUS_PENDING    => 'Pending',
                Order::STATUS_PROCESSING => 'Processing',
                Order::STATUS_SHIPPED    => 'Shipped',
                Order::STATUS_DELIVERED  => 'Delivered',
                Order::STATUS_CANCELLED  => 'Cancelled',
            ] as $value => $label)
                <option value="{{ $value }}" @selected(($status ?? '') == $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <button class="btn btn-secondary btn-sm w-100">Search</button>
    </div>
    @isset($isSearch)
        <div class="col-md-2">
            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary btn-sm w-100">Clear</a>
        </div>
    @endisset
</form>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Order #</th><th>Customer</th><th>Total</th>
                    <th>Status</th><th>Date</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>
                            <a href="{{ route('orders.show', $order->id) }}">
                                {{ $order->order_number }}
                            </a>
                        </td>
                        <td>{{ $order->customer?->name ?? 'N/A' }}</td>
                        <td>${{ number_format($order->total, 2) }}</td>
                        <td>
                            <span class="badge bg-{{ $order->status_color }}">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                        <td class="d-flex gap-1">
                            <a href="{{ route('orders.show', $order->id) }}"
                                class="btn btn-outline-secondary btn-sm">View</a>

                            @if(!in_array($order->status, [Order::STATUS_DELIVERED, Order::STATUS_CANCELLED]))
                                <a href="{{ route('orders.edit', $order->id) }}"
                                    class="btn btn-outline-primary btn-sm">Edit</a>
                            @endif

                            @can('admin')
                                <form action="{{ route('orders.destroy', $order->id) }}" method="POST"
                                      onsubmit="return confirm('Delete this order?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm">Del</button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No orders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@isset($isSearch)
@else
    <div class="mt-3">{{ $orders->links() }}</div>
@endisset

@endsection
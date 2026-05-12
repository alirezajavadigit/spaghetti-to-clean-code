@php use App\Models\Order; @endphp

@extends('layouts.app')

@section('title', $customer->name)

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h4>{{ $customer->name }}</h4>
    <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-outline-primary btn-sm">Edit</a>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header">Info</div>
            <div class="card-body">
                @if($customer->company)
                    <div><strong>Company:</strong> {{ $customer->company }}</div>
                @endif
                <div><strong>Email:</strong> {{ $customer->email }}</div>
                <div><strong>Phone:</strong> {{ $customer->phone ?? '—' }}</div>
                <div><strong>Address:</strong> {{ $customer->address ?? '—' }}</div>
                <div><strong>Credit Limit:</strong> ${{ number_format($customer->credit_limit, 2) }}</div>
                <div><strong>Total Spent:</strong> ${{ number_format($customer->total_spent, 2) }}</div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Orders ({{ $orders->count() }})</div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr><th>Order #</th><th>Total</th><th>Status</th><th>Date</th></tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>
                                    <a href="{{ route('orders.show', $order->id) }}">
                                        {{ $order->order_number }}
                                    </a>
                                </td>
                                <td>${{ number_format($order->total, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $order->status_color }}">
                                        {{ $order->status_label }}
                                    </span>
                                </td>
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No orders yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
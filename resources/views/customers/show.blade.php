@extends('layouts.app')
@section('title', 'Customer')
@section('content')
<div class="d-flex justify-content-between mb-3"><h4>{{ $customer->name }}</h4><a href="/customers/{{ $customer->id }}/edit" class="btn btn-outline-primary btn-sm">Edit</a></div>
<div class="row">
    <div class="col-md-4">
        <div class="card mb-3"><div class="card-header">Info</div><div class="card-body">
            @if($customer->company)<div><strong>Company:</strong> {{ $customer->company }}</div>@endif
            <div><strong>Email:</strong> {{ $customer->email }}</div>
            <div><strong>Phone:</strong> {{ $customer->phone??'—' }}</div>
            <div><strong>Address:</strong> {{ $customer->address??'—' }}</div>
            <div><strong>Credit Limit:</strong> ${{ number_format($customer->credit_limit,2) }}</div>
            <div><strong>Total Spent:</strong> ${{ number_format($totalSpent,2) }}</div>
        </div></div>
    </div>
    <div class="col-md-8">
        <div class="card"><div class="card-header">Orders ({{ count($orders) }})</div><div class="card-body p-0">
            <table class="table mb-0">
                <thead><tr><th>Order #</th><th>Total</th><th>Status</th><th>Date</th></tr></thead>
                <tbody>
                @forelse($orders as $o)
                <tr>
                    <td><a href="/orders/{{ $o->id }}">{{ $o->order_number }}</a></td>
                    <td>${{ number_format($o->total,2) }}</td>
                    <td>@if($o->status==1)<span class="badge bg-secondary">Pending</span>@elseif($o->status==2)<span class="badge bg-primary">Processing</span>@elseif($o->status==3)<span class="badge bg-info">Shipped</span>@elseif($o->status==4)<span class="badge bg-success">Delivered</span>@elseif($o->status==5)<span class="badge bg-danger">Cancelled</span>@endif</td>
                    <td>{{ date('M d, Y',strtotime($o->created_at)) }}</td>
                </tr>
                @empty<tr><td colspan="4" class="text-center text-muted">No orders yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div></div>
    </div>
</div>
@endsection

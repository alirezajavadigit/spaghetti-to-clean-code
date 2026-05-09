@extends('layouts.app')
@section('title', 'Orders')
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h4>Orders</h4>
    <a href="/orders/create" class="btn btn-primary btn-sm">+ New Order</a>
</div>
<form method="GET" action="/orders/search" class="row g-2 mb-3">
    <div class="col-md-5"><input type="text" name="q" class="form-control form-control-sm" placeholder="Search orders or customers..." value="{{ $q ?? '' }}"></div>
    <div class="col-md-3">
        <select name="status" class="form-select form-select-sm">
            <option value="">All Statuses</option>
            <option value="1" {{ isset($status)&&$status=='1'?'selected':'' }}>Pending</option>
            <option value="2" {{ isset($status)&&$status=='2'?'selected':'' }}>Processing</option>
            <option value="3" {{ isset($status)&&$status=='3'?'selected':'' }}>Shipped</option>
            <option value="4" {{ isset($status)&&$status=='4'?'selected':'' }}>Delivered</option>
            <option value="5" {{ isset($status)&&$status=='5'?'selected':'' }}>Cancelled</option>
        </select>
    </div>
    <div class="col-md-2"><button class="btn btn-secondary btn-sm w-100">Search</button></div>
    @if(isset($isSearch))<div class="col-md-2"><a href="/orders" class="btn btn-outline-secondary btn-sm w-100">Clear</a></div>@endif
</form>
<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light"><tr><th>Order #</th><th>Customer</th><th>Total</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
            <tbody>
            @forelse($orders as $order)
            <tr>
                <td><a href="/orders/{{ $order->id }}">{{ $order->order_number }}</a></td>
                <td>{{ isset($isSearch)?$order->customer_name:$order->customer->name }}</td>
                <td>${{ number_format($order->total,2) }}</td>
                <td>
                    @if($order->status==1)<span class="badge bg-secondary">Pending</span>
                    @elseif($order->status==2)<span class="badge bg-primary">Processing</span>
                    @elseif($order->status==3)<span class="badge bg-info">Shipped</span>
                    @elseif($order->status==4)<span class="badge bg-success">Delivered</span>
                    @elseif($order->status==5)<span class="badge bg-danger">Cancelled</span>
                    @endif
                </td>
                <td>{{ date('M d, Y',strtotime($order->created_at)) }}</td>
                <td>
                    <a href="/orders/{{ $order->id }}" class="btn btn-outline-secondary btn-sm">View</a>
                    @if($order->status!=4&&$order->status!=5)<a href="/orders/{{ $order->id }}/edit" class="btn btn-outline-primary btn-sm">Edit</a>@endif
                    @if(Auth::user()->role=='admin')<a href="/orders/{{ $order->id }}/delete" class="btn btn-outline-danger btn-sm" onclick="return confirm('Delete this order?')">Del</a>@endif
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center text-muted">No orders found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@if(!isset($isSearch))<div class="mt-3">{{ $orders->links() }}</div>@endif
@endsection

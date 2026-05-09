@extends('layouts.app')
@section('title', 'Order')
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h4>{{ $order->order_number }}</h4>
    <div>
        @if($order->status!=4&&$order->status!=5)<a href="/orders/{{ $order->id }}/edit" class="btn btn-outline-primary btn-sm">Edit</a>@endif
        @if(Auth::user()->role=='admin')<a href="/orders/{{ $order->id }}/delete" class="btn btn-outline-danger btn-sm" onclick="return confirm('Delete?')">Delete</a>@endif
    </div>
</div>
<div class="row">
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header">Order Items</div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead><tr><th>Product</th><th>Price</th><th>Qty</th><th>Line Total</th></tr></thead>
                    <tbody>
                    @foreach($items as $item)
                    <tr>
                        <td>{{ $products[$item->id]->name??'N/A' }}<br><small class="text-muted">{{ $products[$item->id]->sku??'' }}</small></td>
                        <td>${{ number_format($item->price,2) }}</td><td>{{ $item->quantity }}</td>
                        <td>${{ number_format($item->price*$item->quantity,2) }}</td>
                    </tr>
                    @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr><td colspan="3" class="text-end">Subtotal</td><td>${{ number_format($order->subtotal,2) }}</td></tr>
                        <tr><td colspan="3" class="text-end">Tax (8.5%)</td><td>${{ number_format($order->tax,2) }}</td></tr>
                        <tr><td colspan="3" class="text-end fw-bold">Total</td><td class="fw-bold">${{ number_format($order->total,2) }}</td></tr>
                    </tfoot>
                </table>
            </div>
        </div>
        @if($order->notes)<div class="card mb-3"><div class="card-header">Notes</div><div class="card-body">{!! $order->notes !!}</div></div>@endif
    </div>
    <div class="col-md-4">
        <div class="card mb-3"><div class="card-header">Customer</div><div class="card-body"><strong>{{ $customer->name }}</strong><br>{{ $customer->company }}<br>{{ $customer->email }}<br>{{ $customer->phone }}</div></div>
        <div class="card mb-3">
            <div class="card-header">Status</div>
            <div class="card-body">
                <div class="mb-2">
                    @if($order->status==1)<span class="badge bg-secondary fs-6">Pending</span>
                    @elseif($order->status==2)<span class="badge bg-primary fs-6">Processing</span>
                    @elseif($order->status==3)<span class="badge bg-info fs-6">Shipped</span>
                    @elseif($order->status==4)<span class="badge bg-success fs-6">Delivered</span>
                    @elseif($order->status==5)<span class="badge bg-danger fs-6">Cancelled</span>
                    @endif
                </div>
                <form method="POST" action="/orders/{{ $order->id }}/status">@csrf
                    <select name="status" class="form-select form-select-sm mb-2">
                        <option value="1" {{ $order->status==1?'selected':'' }}>Pending</option>
                        <option value="2" {{ $order->status==2?'selected':'' }}>Processing</option>
                        <option value="3" {{ $order->status==3?'selected':'' }}>Shipped</option>
                        <option value="4" {{ $order->status==4?'selected':'' }}>Delivered</option>
                        <option value="5" {{ $order->status==5?'selected':'' }}>Cancelled</option>
                    </select>
                    <button class="btn btn-secondary btn-sm w-100">Update Status</button>
                </form>
            </div>
        </div>
        <div class="card mb-3"><div class="card-header">Details</div><div class="card-body">
            <small class="text-muted">Created by</small><br>{{ $createdBy }}<br>
            <small class="text-muted">Date</small><br>{{ date('M d, Y',strtotime($order->created_at)) }}<br>
            @if($order->due_date)<small class="text-muted">Due</small><br>{{ date('M d, Y',strtotime($order->due_date)) }}<br>@endif
            <small class="text-muted">Ship to</small><br>{{ $order->shipping_address }}
        </div></div>
        @if($order->attachment)<div class="card"><div class="card-body"><a href="/uploads/{{ $order->attachment }}" target="_blank">📎 {{ $order->attachment }}</a></div></div>@endif
    </div>
</div>
@endsection

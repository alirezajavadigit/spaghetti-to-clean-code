@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<h4 class="mb-4">Dashboard</h4>
<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="card text-white bg-success"><div class="card-body"><div class="small">Total Revenue</div><div class="fs-4">${{ number_format($totalRevenue, 2) }}</div></div></div></div>
    <div class="col-md-3"><div class="card text-white bg-primary"><div class="card-body"><div class="small">Total Orders</div><div class="fs-4">{{ $totalOrders }}</div></div></div></div>
    <div class="col-md-3"><div class="card text-white bg-info"><div class="card-body"><div class="small">Avg Order Value</div><div class="fs-4">${{ number_format($avgOrderValue, 2) }}</div></div></div></div>
    <div class="col-md-3"><div class="card text-white bg-secondary"><div class="card-body"><div class="small">Customers</div><div class="fs-4">{{ $totalCustomers }}</div></div></div></div>
</div>
<div class="row g-3 mb-4">
    <div class="col-md-2"><div class="card text-center"><div class="card-body py-2"><div class="small text-muted">Pending</div><div class="fw-bold text-secondary">{{ $pendingCount }}</div></div></div></div>
    <div class="col-md-2"><div class="card text-center"><div class="card-body py-2"><div class="small text-muted">Processing</div><div class="fw-bold text-primary">{{ $processingCount }}</div></div></div></div>
    <div class="col-md-2"><div class="card text-center"><div class="card-body py-2"><div class="small text-muted">Shipped</div><div class="fw-bold text-info">{{ $shippedCount }}</div></div></div></div>
    <div class="col-md-2"><div class="card text-center"><div class="card-body py-2"><div class="small text-muted">Delivered</div><div class="fw-bold text-success">{{ $deliveredCount }}</div></div></div></div>
    <div class="col-md-2"><div class="card text-center"><div class="card-body py-2"><div class="small text-muted">Cancelled</div><div class="fw-bold text-danger">{{ $cancelledCount }}</div></div></div></div>
</div>
<div class="row g-3">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Recent Orders</div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead><tr><th>Order #</th><th>Customer</th><th>Total</th><th>Status</th><th>Date</th></tr></thead>
                    <tbody>
                    @foreach($recentOrders as $o)
                    <tr>
                        <td><a href="/orders/{{ $o->id }}">{{ $o->order_number }}</a></td>
                        <td>{{ $o->cname }}</td>
                        <td>${{ number_format($o->total, 2) }}</td>
                        <td>
                            @if($o->status==1)<span class="badge bg-secondary">Pending</span>
                            @elseif($o->status==2)<span class="badge bg-primary">Processing</span>
                            @elseif($o->status==3)<span class="badge bg-info">Shipped</span>
                            @elseif($o->status==4)<span class="badge bg-success">Delivered</span>
                            @elseif($o->status==5)<span class="badge bg-danger">Cancelled</span>
                            @endif
                        </td>
                        <td>{{ date('M d', strtotime($o->created_at)) }}</td>
                    </tr>
                    @endforeach
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
                    <thead><tr><th>Product</th><th>Stock</th></tr></thead>
                    <tbody>
                    @foreach($lowStockProducts as $p)
                    <tr>
                        <td>{{ $p->name }}</td>
                        <td><span class="badge {{ $p->stock==0?'bg-danger':'bg-warning text-dark' }}">{{ $p->stock }}</span></td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')
@section('title', 'Reports')
@section('content')
<div class="d-flex justify-content-between mb-3"><h4>Reports</h4><a href="/reports/export" class="btn btn-outline-success btn-sm">Export CSV</a></div>
<form method="GET" action="/reports" class="row g-2 mb-4">
    <div class="col-md-3"><label class="small">From</label><input type="date" name="from" class="form-control form-control-sm" value="{{ $from }}"></div>
    <div class="col-md-3"><label class="small">To</label><input type="date" name="to" class="form-control form-control-sm" value="{{ $to }}"></div>
    <div class="col-md-2 d-flex align-items-end"><button class="btn btn-secondary btn-sm w-100">Filter</button></div>
</form>
<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="card text-white bg-success"><div class="card-body"><div class="small">Total Revenue</div><div class="fs-4">${{ number_format($totalRevenue,2) }}</div></div></div></div>
    <div class="col-md-3"><div class="card text-white bg-primary"><div class="card-body"><div class="small">Total Orders</div><div class="fs-4">{{ $totalOrders }}</div></div></div></div>
    <div class="col-md-3"><div class="card text-white bg-info"><div class="card-body"><div class="small">Avg Order Value</div><div class="fs-4">${{ number_format($avgOrderValue,2) }}</div></div></div></div>
    <div class="col-md-3"><div class="card text-white bg-danger"><div class="card-body"><div class="small">Cancelled</div><div class="fs-4">{{ $cancelledCount }}</div></div></div></div>
</div>
<div class="row g-3 mb-4">
    <div class="col-md-6"><div class="card"><div class="card-header">Revenue by Month</div><div class="card-body p-0">
        <table class="table table-sm mb-0"><thead><tr><th>Month</th><th>Orders</th><th>Revenue</th></tr></thead><tbody>
        @forelse($revenueByMonth as $row)<tr><td>{{ $row->month }}</td><td>{{ $row->orders }}</td><td>${{ number_format($row->revenue,2) }}</td></tr>
        @empty<tr><td colspan="3" class="text-center text-muted">No data.</td></tr>@endforelse
        </tbody></table>
    </div></div></div>
    <div class="col-md-6"><div class="card"><div class="card-header">Revenue by Category</div><div class="card-body p-0">
        <table class="table table-sm mb-0"><thead><tr><th>Category</th><th>Revenue</th><th>Share</th></tr></thead><tbody>
        @forelse($categoryRevenue as $row)<tr><td>{{ $row->category }}</td><td>${{ number_format($row->revenue,2) }}</td><td>{{ $totalRevenue>0?number_format(($row->revenue/$totalRevenue)*100,1):0 }}%</td></tr>
        @empty<tr><td colspan="3" class="text-center text-muted">No data.</td></tr>@endforelse
        </tbody></table>
    </div></div></div>
</div>
<div class="row g-3">
    <div class="col-md-6"><div class="card"><div class="card-header">Top Products</div><div class="card-body p-0">
        <table class="table table-sm mb-0"><thead><tr><th>Product</th><th>Units Sold</th><th>Revenue</th></tr></thead><tbody>
        @foreach($topProducts as $p)<tr><td>{{ $p->name }}<br><small class="text-muted">{{ $p->sku }}</small></td><td>{{ $p->units_sold }}</td><td>${{ number_format($p->revenue,2) }}</td></tr>@endforeach
        </tbody></table>
    </div></div></div>
    <div class="col-md-6"><div class="card"><div class="card-header">Top Customers</div><div class="card-body p-0">
        <table class="table table-sm mb-0"><thead><tr><th>Customer</th><th>Orders</th><th>Spent</th></tr></thead><tbody>
        @foreach($topCustomers as $c)<tr><td>{{ $c->name }}<br><small class="text-muted">{{ $c->company }}</small></td><td>{{ $c->order_count }}</td><td>${{ number_format($c->total_spent,2) }}</td></tr>@endforeach
        </tbody></table>
    </div></div></div>
</div>
@endsection

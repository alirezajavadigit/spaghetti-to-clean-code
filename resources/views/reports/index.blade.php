@extends('layouts.app')

@section('title', 'Reports')

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h4>Reports</h4>
    <a href="{{ route('reports.export') }}" class="btn btn-outline-success btn-sm">Export CSV</a>
</div>

<form method="GET" action="{{ route('reports.index') }}" class="row g-2 mb-4">
    <div class="col-md-3">
        <label class="small">From</label>
        <input type="date" name="from" class="form-control form-control-sm" value="{{ $from }}">
    </div>
    <div class="col-md-3">
        <label class="small">To</label>
        <input type="date" name="to" class="form-control form-control-sm" value="{{ $to }}">
    </div>
    <div class="col-md-2 d-flex align-items-end">
        <button class="btn btn-secondary btn-sm w-100">Filter</button>
    </div>
</form>

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
        <div class="card text-white bg-danger">
            <div class="card-body">
                <div class="small">Cancelled</div>
                <div class="fs-4">{{ $cancelled_count }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Revenue by Month</div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead><tr><th>Month</th><th>Orders</th><th>Revenue</th></tr></thead>
                    <tbody>
                        @forelse($revenue_by_month as $row)
                            <tr>
                                <td>{{ $row->month }}</td>
                                <td>{{ $row->orders }}</td>
                                <td>${{ number_format($row->revenue, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted">No data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Revenue by Category</div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead><tr><th>Category</th><th>Revenue</th><th>Share</th></tr></thead>
                    <tbody>
                        @forelse($category_revenue as $row)
                            <tr>
                                <td>{{ $row->category ?? '—' }}</td>
                                <td>${{ number_format($row->revenue, 2) }}</td>
                                <td>
                                    {{ $total_revenue > 0
                                        ? number_format(($row->revenue / $total_revenue) * 100, 1)
                                        : 0 }}%
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted">No data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Top Products</div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead><tr><th>Product</th><th>Units Sold</th><th>Revenue</th></tr></thead>
                    <tbody>
                        @foreach($top_products as $item)
                            <tr>
                                <td>
                                    {{ $item->product?->name ?? 'N/A' }}<br>
                                    <small class="text-muted">{{ $item->product?->sku }}</small>
                                </td>
                                <td>{{ $item->units_sold }}</td>
                                <td>${{ number_format($item->revenue, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Top Customers</div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead><tr><th>Customer</th><th>Orders</th><th>Spent</th></tr></thead>
                    <tbody>
                        @foreach($top_customers as $customer)
                            <tr>
                                <td>
                                    {{ $customer->name }}<br>
                                    <small class="text-muted">{{ $customer->company }}</small>
                                </td>
                                <td>{{ $customer->orders_count }}</td>
                                <td>${{ number_format($customer->orders_sum_total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
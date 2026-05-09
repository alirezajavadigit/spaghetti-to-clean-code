@extends('layouts.app')
@section('title', 'Customers')
@section('content')
<div class="d-flex justify-content-between mb-3"><h4>Customers</h4><a href="/customers/new" class="btn btn-primary btn-sm">+ New Customer</a></div>
<div class="card"><div class="card-body p-0">
    <table class="table table-hover mb-0">
        <thead class="table-light"><tr><th>Name</th><th>Company</th><th>Email</th><th>Phone</th><th>Credit Limit</th><th>Actions</th></tr></thead>
        <tbody>
        @forelse($customers as $c)
        <tr>
            <td><a href="/customers/{{ $c->id }}">{{ $c->name }}</a></td><td>{{ $c->company??'—' }}</td>
            <td>{{ $c->email }}</td><td>{{ $c->phone??'—' }}</td><td>${{ number_format($c->credit_limit,2) }}</td>
            <td>
                <a href="/customers/{{ $c->id }}/edit" class="btn btn-outline-primary btn-sm">Edit</a>
                @if(Auth::user()->role=='admin')<a href="/customers/{{ $c->id }}/delete" class="btn btn-outline-danger btn-sm" onclick="return confirm('Delete?')">Del</a>@endif
            </td>
        </tr>
        @empty<tr><td colspan="6" class="text-center text-muted">No customers found.</td></tr>
        @endforelse
        </tbody>
    </table>
</div></div>
<div class="mt-3">{{ $customers->links() }}</div>
@endsection

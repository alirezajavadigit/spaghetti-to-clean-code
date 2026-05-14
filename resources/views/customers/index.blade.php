@extends('layouts.app')

@section('title', 'Customers')

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h4>Customers</h4>
    <a href="{{ route('customers.create') }}" class="btn btn-primary btn-sm">+ New Customer</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Name</th><th>Company</th><th>Email</th>
                    <th>Phone</th><th>Credit Limit</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                    <tr>
                        <td>
                            <a href="{{ route('customers.show', $customer->id) }}">
                                {{ $customer->name }}
                            </a>
                        </td>
                        <td>{{ $customer->company ?? '—' }}</td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->phone ?? '—' }}</td>
                        <td>${{ number_format($customer->credit_limit, 2) }}</td>
                        <td class="d-flex gap-1">
                            <a href="{{ route('customers.edit', $customer->id) }}"
                                class="btn btn-outline-primary btn-sm">Edit</a>

                            @can('admin')
                                <form action="{{ route('customers.destroy', $customer->id) }}" method="POST"
                                      onsubmit="return confirm('Delete this customer?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm">Del</button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No customers found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $customers->links() }}</div>

@endsection
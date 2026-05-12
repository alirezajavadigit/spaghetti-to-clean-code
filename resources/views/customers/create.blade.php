@extends('layouts.app')

@section('title', 'New Customer')

@section('content')

<h4 class="mb-4">New Customer</h4>

<form method="POST" action="{{ route('customers.store') }}">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Name *</label>
                    <input type="text" name="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Email *</label>
                    <input type="email" name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone"
                        class="form-control @error('phone') is-invalid @enderror"
                        value="{{ old('phone') }}">
                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Company</label>
                    <input type="text" name="company" class="form-control"
                        value="{{ old('company') }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Credit Limit</label>
                    <input type="number" step="0.01" name="credit_limit"
                        class="form-control @error('credit_limit') is-invalid @enderror"
                        value="{{ old('credit_limit', 0) }}">
                    @error('credit_limit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12 mb-3">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-control"
                        rows="2">{{ old('address') }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3 d-flex gap-2">
        <button type="submit" class="btn btn-primary">Create Customer</button>
        <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </div>
</form>

@endsection
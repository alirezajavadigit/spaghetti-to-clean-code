@extends('layouts.app')
@section('title', 'New Customer')
@section('content')
<h4 class="mb-4">New Customer</h4>
<form method="POST" action="/customers/new">
    @csrf
    <div class="card"><div class="card-body"><div class="row">
        <div class="col-md-6 mb-3"><label>Name *</label><input type="text" name="name" class="form-control" value="{{ old('name') }}"></div>
        <div class="col-md-6 mb-3"><label>Email *</label><input type="text" name="email" class="form-control" value="{{ old('email') }}"></div>
        <div class="col-md-6 mb-3"><label>Phone</label><input type="text" name="phone" class="form-control" value="{{ old('phone') }}"></div>
        <div class="col-md-6 mb-3"><label>Company</label><input type="text" name="company" class="form-control" value="{{ old('company') }}"></div>
        <div class="col-md-6 mb-3"><label>Credit Limit</label><input type="number" name="credit_limit" class="form-control" value="{{ old('credit_limit',0) }}"></div>
        <div class="col-12 mb-3"><label>Address</label><textarea name="address" class="form-control" rows="2">{{ old('address') }}</textarea></div>
    </div></div></div>
    <div class="mt-3"><button type="submit" class="btn btn-primary">Create Customer</button><a href="/customers" class="btn btn-outline-secondary">Cancel</a></div>
</form>
@endsection

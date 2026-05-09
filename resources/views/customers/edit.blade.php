@extends('layouts.app')
@section('title', 'Edit Customer')
@section('content')
<h4 class="mb-4">Edit Customer</h4>
<form method="POST" action="/customers/{{ $customer->id }}/edit">
    @csrf
    <div class="card"><div class="card-body"><div class="row">
        <div class="col-md-6 mb-3"><label>Name *</label><input type="text" name="name" class="form-control" value="{{ $customer->name }}"></div>
        <div class="col-md-6 mb-3"><label>Email *</label><input type="text" name="email" class="form-control" value="{{ $customer->email }}"></div>
        <div class="col-md-6 mb-3"><label>Phone</label><input type="text" name="phone" class="form-control" value="{{ $customer->phone }}"></div>
        <div class="col-md-6 mb-3"><label>Company</label><input type="text" name="company" class="form-control" value="{{ $customer->company }}"></div>
        <div class="col-md-6 mb-3"><label>Credit Limit</label><input type="number" name="credit_limit" class="form-control" value="{{ $customer->credit_limit }}"></div>
        <div class="col-12 mb-3"><label>Address</label><textarea name="address" class="form-control" rows="2">{{ $customer->address }}</textarea></div>
    </div></div></div>
    <div class="mt-3"><button type="submit" class="btn btn-primary">Save Changes</button><a href="/customers" class="btn btn-outline-secondary">Cancel</a></div>
</form>
@endsection

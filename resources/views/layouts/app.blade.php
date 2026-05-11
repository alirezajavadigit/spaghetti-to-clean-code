<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OMS - @yield('title', 'Dashboard')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { background: #f8f9fa; }
        .sidebar { min-height: 100vh; background: #343a40; }
        .sidebar a { color: #adb5bd; text-decoration: none; display: block; padding: 10px 20px; }
        .sidebar a:hover { color: #fff; background: #495057; }
        .sidebar .brand { color: #fff; font-size: 1.2rem; font-weight: bold; padding: 20px; border-bottom: 1px solid #495057; }
        .sidebar .nav-logout { color: #dc3545; border-top: 1px solid #495057; margin-top: 10px; }
    </style>
</head>
<body>
<div class="d-flex">
    <div class="sidebar" style="width:220px; min-width:220px;">
        <div class="brand">OMS</div>
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <a href="{{ route('orders.index') }}">Orders</a>
        <a href="{{ route('products.index') }}">Products</a>
        <a href="{{ route('customers.index') }}">Customers</a>
        @can('admin')
            <a href="{{ route('reports.index') }}">Reports</a>
        @endcan
        <form action="{{ route('logout') }}" method="POST" class="nav-logout">
            @csrf
            <button type="submit"
                style="background:none; border:none; color:#dc3545; padding:10px 20px; width:100%; text-align:left; cursor:pointer;">
                Logout
            </button>
        </form>
    </div>

    <div class="flex-grow-1 p-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
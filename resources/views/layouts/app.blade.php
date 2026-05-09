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
    </style>
</head>
<body>
<div class="d-flex">
    <div class="sidebar" style="width:220px; min-width:220px;">
        <div class="brand">OMS</div>
        <a href="/dashboard">Dashboard</a>
        <a href="/orders">Orders</a>
        <a href="/products">Products</a>
        <a href="/customers">Customers</a>
        @if(Auth::check() && Auth::user()->role == 'admin')
        <a href="/reports">Reports</a>
        @endif
        <div style="border-top:1px solid #495057; margin-top:auto; padding-top:10px;">
            <a href="/logout" style="color:#dc3545;">Logout</a>
        </div>
    </div>
    <div class="flex-grow-1 p-4">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @yield('content')
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

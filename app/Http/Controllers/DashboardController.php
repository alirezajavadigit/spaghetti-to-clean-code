<?php

namespace App\Http\Controllers;

use App\Services\Dashboard\DashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private readonly DashboardService $dashboardService) {}

    public function index(): View
    {
        return view('dashboard.index', [
            ...$this->dashboardService->summary(),
            'recent_orders'      => $this->dashboardService->recentOrders(),
            'low_stock_products' => $this->dashboardService->lowStockProducts(),
            'top_customers'      => $this->dashboardService->topCustomers(),
        ]);
    }
}

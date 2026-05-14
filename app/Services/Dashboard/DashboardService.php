<?php

namespace App\Services\Dashboard;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Repositories\Contracts\CustomerRepositoryInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Services\Report\ReportService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class DashboardService
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly ProductRepositoryInterface $productRepository,
        private readonly CustomerRepositoryInterface $customerRepository,
        private readonly ReportService $reportService,
    ) {}

    public function summary(): array
    {
        return Cache::remember('dashboard.summary', now()->addMinutes(10), function () {
            $base = $this->reportService->summary();

            return array_merge($base, [
                'total_customers' => $this->customerRepository->count(),
                'total_products'  => Product::count(),
            ]);
        });
    }

    public function recentOrders(int $limit = 5): Collection
    {
        return $this->orderRepository->recent($limit);
    }

    public function lowStockProducts(int $threshold = 10): Collection
    {
        return $this->productRepository->lowStock($threshold);
    }

    public function topCustomers(int $limit = 5): Collection
    {
        return Customer::withCount(['orders' => fn($q) => $q->where('status', '!=', Order::STATUS_CANCELLED)])
            ->withSum(['orders' => fn($q) => $q->where('status', '!=', Order::STATUS_CANCELLED)], 'total')
            ->orderByDesc('orders_sum_total')
            ->limit($limit)
            ->get();
    }
}

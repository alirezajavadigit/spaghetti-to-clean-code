<?php

namespace App\Services\Dashboard;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Repositories\Contracts\CustomerRepositoryInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Support\Collection;

class DashboardService
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly ProductRepositoryInterface $productRepository,
        private readonly CustomerRepositoryInterface $customerRepository,
    ) {}

    public function summary(): array
    {
        $stats = Order::selectRaw('
            SUM(CASE WHEN status != ? THEN total ELSE 0 END) as total_revenue,
            COUNT(*) as total_orders,
            SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as pending_count,
            SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as processing_count,
            SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as shipped_count,
            SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as delivered_count,
            SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as cancelled_count
        ', [
            Order::STATUS_CANCELLED,
            Order::STATUS_PENDING,
            Order::STATUS_PROCESSING,
            Order::STATUS_SHIPPED,
            Order::STATUS_DELIVERED,
            Order::STATUS_CANCELLED,
        ])->first();

        $totalRevenue = (float) $stats->total_revenue;
        $totalOrders  = (int) $stats->total_orders;

        return [
            'total_revenue'    => $totalRevenue,
            'total_orders'     => $totalOrders,
            'avg_order_value'  => $totalOrders > 0 ? round($totalRevenue / $totalOrders, 2) : 0,
            'pending_count'    => (int) $stats->pending_count,
            'processing_count' => (int) $stats->processing_count,
            'shipped_count'    => (int) $stats->shipped_count,
            'delivered_count'  => (int) $stats->delivered_count,
            'cancelled_count'  => (int) $stats->cancelled_count,
            'total_customers'  => $this->customerRepository->count(),
            'total_products'   => Product::count(),
        ];
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

<?php

namespace App\Services\Report;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ReportService
{
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
        ];
    }

    public function revenueByMonth(string $from, string $to): Collection
    {
        return Order::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, SUM(total) as revenue, COUNT(id) as orders")
            ->where('status', '!=', Order::STATUS_CANCELLED)
            ->whereBetween(DB::raw('DATE(created_at)'), [$from, $to])
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }

    public function topProducts(int $limit = 10): Collection
    {
        return OrderItem::selectRaw('product_id, SUM(quantity) as units_sold, SUM(quantity * price) as revenue')
            ->whereHas('order', fn($q) => $q->where('status', '!=', Order::STATUS_CANCELLED))
            ->with('product:id,name,sku')
            ->groupBy('product_id')
            ->orderByDesc('revenue')
            ->limit($limit)
            ->get();
    }

    public function topCustomers(int $limit = 10): Collection
    {
        return Customer::withCount(['orders' => fn($q) => $q->where('status', '!=', Order::STATUS_CANCELLED)])
            ->withSum(['orders' => fn($q) => $q->where('status', '!=', Order::STATUS_CANCELLED)], 'total')
            ->orderByDesc('orders_sum_total')
            ->limit($limit)
            ->get();
    }

    public function categoryRevenue(): Collection
    {
        return OrderItem::selectRaw('products.category, SUM(order_items.quantity * order_items.price) as revenue')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', '!=', Order::STATUS_CANCELLED)
            ->groupBy('products.category')
            ->get();
    }

    public function exportOrders(): Collection
    {
        return Order::with('customer:id,name')
            ->select('order_number', 'customer_id', 'total', 'status', 'created_at')
            ->latest()
            ->get();
    }
}

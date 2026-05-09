<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order; use App\Models\Customer; use App\Models\Product;
class DashboardController extends Controller {
    public function index() {
        if (!Auth::check()) return redirect('/login');
        $orders = Order::all();
        $totalRevenue=$pendingCount=$processingCount=$shippedCount=$deliveredCount=$cancelledCount=0;
        foreach ($orders as $order) {
            if ($order->status!=5) $totalRevenue+=$order->total;
            if ($order->status==1) $pendingCount++;
            if ($order->status==2) $processingCount++;
            if ($order->status==3) $shippedCount++;
            if ($order->status==4) $deliveredCount++;
            if ($order->status==5) $cancelledCount++;
        }
        $totalOrders=count($orders);
        $avgOrderValue=$totalOrders>0?$totalRevenue/$totalOrders:0;
        $recentOrders=DB::select('SELECT orders.*,customers.name as cname FROM orders LEFT JOIN customers ON orders.customer_id=customers.id ORDER BY orders.created_at DESC LIMIT 5');
        $lowStockProducts=Product::all()->filter(fn($p)=>$p->stock<10);
        $topCustomers=DB::select('SELECT customers.name,COUNT(orders.id) as order_count,SUM(orders.total) as spent FROM customers LEFT JOIN orders ON customers.id=orders.customer_id GROUP BY customers.id ORDER BY spent DESC LIMIT 5');
        $totalCustomers=Customer::all()->count();
        $totalProducts=Product::all()->count();
        return view('dashboard.index',compact('totalRevenue','totalOrders','avgOrderValue','pendingCount','processingCount','shippedCount','deliveredCount','cancelledCount','recentOrders','lowStockProducts','topCustomers','totalCustomers','totalProducts'));
    }
}

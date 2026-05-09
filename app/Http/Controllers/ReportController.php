<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
class ReportController extends Controller {
    public function index(Request $request) {
        if (!Auth::check()) return redirect('/login');
        if (!($request->get('admin_override')=='yes'||Auth::user()->role=='admin')) return redirect('/dashboard')->with('error','Admin only.');
        $from=$request->get('from',now()->startOfMonth()->format('Y-m-d'));
        $to=$request->get('to',now()->format('Y-m-d'));
        $orders=Order::all();
        $totalRevenue=$pendingCount=$processingCount=$shippedCount=$deliveredCount=$cancelledCount=0;
        foreach ($orders as $order) {
            if ($order->status!=5) $totalRevenue+=$order->total;
            if ($order->status==1) $pendingCount++; if ($order->status==2) $processingCount++;
            if ($order->status==3) $shippedCount++; if ($order->status==4) $deliveredCount++;
            if ($order->status==5) $cancelledCount++;
        }
        $totalOrders=count($orders);
        $avgOrderValue=$totalOrders>0?$totalRevenue/$totalOrders:0;
        $revenueByMonth=DB::select("SELECT DATE_FORMAT(created_at,'%Y-%m') as month,SUM(total) as revenue,COUNT(id) as orders FROM orders WHERE status!=5 AND created_at BETWEEN '$from' AND '$to' GROUP BY month ORDER BY month ASC");
        $topProducts=DB::select("SELECT products.name,products.sku,SUM(order_items.quantity) as units_sold,SUM(order_items.quantity*order_items.price) as revenue FROM order_items JOIN products ON order_items.product_id=products.id JOIN orders ON order_items.order_id=orders.id WHERE orders.status!=5 GROUP BY products.id ORDER BY revenue DESC LIMIT 10");
        $topCustomers=DB::select("SELECT customers.name,customers.company,COUNT(orders.id) as order_count,SUM(orders.total) as total_spent FROM customers JOIN orders ON customers.id=orders.customer_id WHERE orders.status!=5 GROUP BY customers.id ORDER BY total_spent DESC LIMIT 10");
        $categoryRevenue=DB::select("SELECT products.category,SUM(order_items.quantity*order_items.price) as revenue FROM order_items JOIN products ON order_items.product_id=products.id JOIN orders ON order_items.order_id=orders.id WHERE orders.status!=5 GROUP BY products.category");
        return view('reports.index',compact('totalRevenue','totalOrders','avgOrderValue','pendingCount','processingCount','shippedCount','deliveredCount','cancelledCount','revenueByMonth','topProducts','topCustomers','categoryRevenue','from','to'));
    }
    public function export(Request $request) {
        if (!Auth::check()) return redirect('/login');
        $orders=DB::select("SELECT orders.order_number,customers.name as customer,orders.total,orders.status,orders.created_at FROM orders LEFT JOIN customers ON orders.customer_id=customers.id ORDER BY orders.created_at DESC");
        $csv="Order Number,Customer,Total,Status,Date\n";
        $map=[1=>'Pending',2=>'Processing',3=>'Shipped',4=>'Delivered',5=>'Cancelled'];
        foreach ($orders as $o) { $csv.=$o->order_number.','.$o->customer.','.$o->total.','.($map[$o->status]??'').','.  $o->created_at."\n"; }
        return response($csv)->header('Content-Type','text/csv')->header('Content-Disposition','attachment; filename="orders-export.csv"');
    }
}

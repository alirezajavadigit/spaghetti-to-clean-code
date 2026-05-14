<?php

namespace App\Http\Controllers;

use App\Services\Report\ReportService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __construct(private readonly ReportService $reportService) {}

    public function index(Request $request): View
    {
        $from = $request->input('from', now()->startOfMonth()->format('Y-m-d'));
        $to   = $request->input('to', now()->format('Y-m-d'));

        return view('reports.index', [
            ...$this->reportService->summary(),
            'revenue_by_month' => $this->reportService->revenueByMonth($from, $to),
            'top_products'     => $this->reportService->topProducts(),
            'top_customers'    => $this->reportService->topCustomers(),
            'category_revenue' => $this->reportService->categoryRevenue(),
            'from'             => $from,
            'to'               => $to,
        ]);
    }

    public function export(): Response
    {
        $orders = $this->reportService->exportOrders();

        $csv = "Order Number,Customer,Total,Status,Date\n";

        foreach ($orders as $order) {
            $csv .= implode(',', [
                $order->order_number,
                $order->customer?->name ?? 'N/A',
                $order->total,
                $order->status_label,
                $order->created_at->format('Y-m-d'),
            ]) . "\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="orders-export.csv"');
    }
}

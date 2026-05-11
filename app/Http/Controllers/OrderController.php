<?php

namespace App\Http\Controllers;

use App\DTOs\Order\StoreOrderDTO;
use App\DTOs\Order\UpdateOrderDTO;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Http\Requests\Order\UpdateOrderStatusRequest;
use App\Repositories\Contracts\CustomerRepositoryInterface;
use App\Repositories\Contracts\OrderItemRepositoryInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Services\Order\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderService $orderService,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly OrderItemRepositoryInterface $orderItemRepository,
        private readonly CustomerRepositoryInterface $customerRepository,
        private readonly ProductRepositoryInterface $productRepository,
    ) {}

    public function index(): View
    {
        $orders = $this->orderRepository->paginate();
        return view('orders.index', compact('orders'));
    }

    public function create(): View
    {
        $customers = $this->customerRepository->all();
        $products  = $this->productRepository->findActiveWithStock();
        return view('orders.create', compact('customers', 'products'));
    }

    public function store(StoreOrderRequest $request): RedirectResponse
    {
        try {
            $order = $this->orderService->store(StoreOrderDTO::fromRequest($request));
            return redirect()->route('orders.index')->with('success', __('orders.created', ['number' => $order->order_number]));
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show(int $id): View|RedirectResponse
    {
        $order = $this->orderRepository->findById($id);

        if (!$order) {
            return redirect()->route('orders.index')->with('error', __('orders.not_found'));
        }

        $order->load('user');

        $items     = $this->orderItemRepository->findByOrderId($id);
        $customer  = $this->customerRepository->findById($order->customer_id);
        $createdBy = $order->user?->name ?? 'Unknown';

        return view('orders.show', compact('order', 'items', 'customer', 'createdBy'));
    }
    public function edit(int $id): View|RedirectResponse
    {
        $order = $this->orderRepository->findById($id);

        if (in_array($order->status, [4, 5])) {
            return redirect()->route('orders.show', $id)->with('error', __('orders.cannot_edit'));
        }

        $customers = $this->customerRepository->all();
        return view('orders.edit', compact('order', 'customers'));
    }

    public function update(UpdateOrderRequest $request, int $id): RedirectResponse
    {
        $this->orderService->update($id, UpdateOrderDTO::fromRequest($request));
        return redirect()->route('orders.show', $id)->with('success', __('orders.updated'));
    }

    public function destroy(int $id): RedirectResponse
    {
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('orders.index')->with('error', __('orders.admin_only'));
        }

        $this->orderService->delete($id);
        return redirect()->route('orders.index')->with('success', __('orders.deleted'));
    }

    public function search(Request $request): View
    {
        $orders   = $this->orderRepository->search($request->query('q', ''), $request->query('status'));
        $isSearch = true;
        return view('orders.index', compact('orders', 'isSearch'));
    }

    public function updateStatus(UpdateOrderStatusRequest $request, int $id): RedirectResponse
    {
        $this->orderService->updateStatus($id, $request->status);
        return redirect()->route('orders.show', $id)->with('success', __('orders.status_updated'));
    }
}

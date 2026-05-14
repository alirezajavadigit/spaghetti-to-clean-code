<?php

namespace App\Services\Order;

use App\DTOs\Order\StoreOrderDTO;
use App\DTOs\Order\UpdateOrderDTO;
use App\Models\Order;
use App\Models\Product;
use App\Repositories\Contracts\OrderItemRepositoryInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderService
{
    private const TAX_RATE = 0.085;

    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly OrderItemRepositoryInterface $orderItemRepository,
    ) {}

    public function store(StoreOrderDTO $dto): Order
    {
        if ($this->orderRepository->hasPendingForCustomer($dto->customerId)) {
            throw new \RuntimeException(__('orders.pending_exists'));
        }

        return DB::transaction(function () use ($dto) {
            $lineItems = $this->resolveLineItems($dto->products);
            $subtotal  = collect($lineItems)->sum(fn($i) => $i['price'] * $i['qty']);
            $tax       = round($subtotal * self::TAX_RATE, 2);

            $order = $this->orderRepository->create([
                'order_number'     => $this->generateOrderNumber(),
                'customer_id'      => $dto->customerId,
                'user_id'          => Auth::id(),
                'status'           => 1,
                'subtotal'         => $subtotal,
                'tax'              => $tax,
                'total'            => round($subtotal + $tax, 2),
                'notes'            => $dto->notes,
                'shipping_address' => $dto->shippingAddress,
                'due_date'         => $dto->dueDate,
                'attachment'       => $this->uploadAttachment($dto->attachment),
            ]);

            $this->orderItemRepository->createMany($order->id, $lineItems);

            return $order;
        });
    }

    public function update(int $id, UpdateOrderDTO $dto): Order
    {
        return $this->orderRepository->update($id, [
            'customer_id'      => $dto->customerId,
            'notes'            => $dto->notes,
            'shipping_address' => $dto->shippingAddress,
            'due_date'         => $dto->dueDate,
        ]);
    }

    public function updateStatus(int $id, int $status): Order
    {
        return $this->orderRepository->update($id, ['status' => $status]);
    }

    public function delete(int $id): void
    {
        DB::transaction(function () use ($id) {
            $this->orderItemRepository->deleteByOrderId($id);
            $this->orderRepository->delete($id);
        });
    }

    private function resolveLineItems(array $products): array
    {
        $filtered = array_filter($products, fn($qty) => (int) $qty > 0);
        $ids      = array_keys($filtered);

        $productModels = Product::whereIn('id', $ids)->get()->keyBy('id');

        $lineItems = [];

        foreach ($filtered as $productId => $qty) {
            $qty     = (int) $qty;
            $product = $productModels->get($productId);

            if (!$product) continue;

            if ($product->stock < $qty) {
                throw new \RuntimeException(__('products.insufficient_stock', ['name' => $product->name]));
            }

            $lineItems[] = [
                'product_id' => $product->id,
                'qty'        => $qty,
                'price'      => $product->price,
            ];
        }

        if (empty($lineItems)) {
            throw new \RuntimeException(__('orders.no_valid_products'));
        }

        foreach ($lineItems as $item) {
            Product::where('id', $item['product_id'])->decrement('stock', $item['qty']);
        }

        return $lineItems;
    }

    private function generateOrderNumber(): string
    {
        return 'ORD-' . strtoupper(uniqid());
    }

    private function uploadAttachment(mixed $file): string|null
    {
        if (!$file) return null;

        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/orders'), $filename);
        return $filename;
    }
}

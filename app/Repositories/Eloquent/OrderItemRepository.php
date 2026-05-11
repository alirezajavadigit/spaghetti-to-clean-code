<?php

namespace App\Repositories\Eloquent;

use App\Models\OrderItem;
use App\Repositories\Contracts\OrderItemRepositoryInterface;
use Illuminate\Support\Collection;

class OrderItemRepository implements OrderItemRepositoryInterface
{
    public function __construct(private readonly OrderItem $model) {}

    public function createMany(int $orderId, array $items): void
    {
        foreach ($items as $item) {
            $this->model->create([
                'order_id'   => $orderId,
                'product_id' => $item['product_id'],
                'quantity'   => $item['qty'],
                'price'      => $item['price'],
            ]);
        }
    }

    public function findByOrderId(int $orderId): Collection
    {
        return $this->model->where('order_id', $orderId)->get();
    }

    public function deleteByOrderId(int $orderId): void
    {
        $this->model->where('order_id', $orderId)->delete();
    }

    public function existsByProductId(int $productId): bool
    {
        return $this->model->where('product_id', $productId)->exists();
    }
}

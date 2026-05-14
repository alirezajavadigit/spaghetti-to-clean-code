<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;

interface OrderItemRepositoryInterface
{
    public function createMany(int $orderId, array $items): void;
    public function findByOrderId(int $orderId): Collection;
    public function deleteByOrderId(int $orderId): void;
    public function existsByProductId(int $productId): bool;
}

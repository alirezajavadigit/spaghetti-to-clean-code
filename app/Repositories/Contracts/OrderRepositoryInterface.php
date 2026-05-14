<?php

namespace App\Repositories\Contracts;

use App\Models\Order;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface OrderRepositoryInterface
{
    public function paginate(int $perPage = 20): LengthAwarePaginator;
    public function recent(int $limit = 5): Collection;
    public function findById(int $id): ?Order;
    public function create(array $data): Order;
    public function update(int $id, array $data): Order;
    public function delete(int $id): bool;
    public function search(string $query, string|null $status): Collection;
    public function hasPendingForCustomer(int $customerId): bool;
    public function existsByCustomerId(int $customerId): bool;
}

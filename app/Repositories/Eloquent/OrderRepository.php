<?php

namespace App\Repositories\Eloquent;

use App\Models\Order;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class OrderRepository implements OrderRepositoryInterface
{
    public function __construct(private readonly Order $model) {}

    public function paginate(int $perPage = 20): LengthAwarePaginator
    {
        return $this->model->latest()->paginate($perPage);
    }

    public function recent(int $limit = 5): Collection
    {
        return $this->model
            ->with('customer')
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function findById(int $id): ?Order
    {
        return $this->model->find($id);
    }

    public function create(array $data): Order
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Order
    {
        $order = $this->model->findOrFail($id);
        $order->update($data);
        return $order->fresh();
    }

    public function delete(int $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }

    public function search(string $query, string|null $status): Collection
    {
        return $this->model
            ->with('customer')
            ->where(function ($q) use ($query) {
                $q->whereHas('customer', fn($q) => $q->where('name', 'LIKE', "%{$query}%"))
                    ->orWhere('order_number', 'LIKE', "%{$query}%");
            })
            ->when($status !== null && $status !== '', fn($q) => $q->where('status', $status))
            ->latest()
            ->get();
    }

    public function hasPendingForCustomer(int $customerId): bool
    {
        return $this->model
            ->where('customer_id', $customerId)
            ->where('status', 1)
            ->exists();
    }

    public function existsByCustomerId(int $customerId): bool
    {
        return $this->model->where('customer_id', $customerId)->exists();
    }
}

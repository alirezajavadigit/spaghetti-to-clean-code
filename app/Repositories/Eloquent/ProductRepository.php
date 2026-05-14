<?php

namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ProductRepository implements ProductRepositoryInterface
{
    public function __construct(private readonly Product $model) {}

    public function paginate(int $perPage = 20): LengthAwarePaginator
    {
        return $this->model->latest()->paginate($perPage);
    }

    public function lowStock(int $threshold = 10): Collection
    {
        return $this->model
            ->where('stock', '<', $threshold)
            ->where('active', true)
            ->orderBy('stock')
            ->get();
    }
    
    public function search(string $query): Collection
    {
        return $this->model
            ->where('name', 'LIKE', "%{$query}%")
            ->orWhere('sku', 'LIKE', "%{$query}%")
            ->get();
    }

    public function findById(int $id): ?Product
    {
        return $this->model->find($id);
    }

    public function findActiveWithStock(): Collection
    {
        return $this->model
            ->where('active', true)
            ->where('stock', '>', 0)
            ->get();
    }

    public function create(array $data): Product
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Product
    {
        $product = $this->model->findOrFail($id);
        $product->update($data);
        return $product->fresh();
    }

    public function delete(int $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }
}

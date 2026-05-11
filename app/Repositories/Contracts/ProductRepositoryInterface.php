<?php

namespace App\Repositories\Contracts;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ProductRepositoryInterface
{
    public function paginate(int $perPage = 20): LengthAwarePaginator;
    public function search(string $query): Collection;
    public function findById(int $id): ?Product;
    public function findActiveWithStock(): Collection;
    public function create(array $data): Product;
    public function update(int $id, array $data): Product;
    public function delete(int $id): bool;
}

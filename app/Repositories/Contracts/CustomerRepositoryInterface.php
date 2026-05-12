<?php

namespace App\Repositories\Contracts;

use App\Models\Customer;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface CustomerRepositoryInterface
{
    public function paginate(int $perPage = 20): LengthAwarePaginator;
    public function all(): Collection;
    public function findById(int $id): ?Customer;
    public function findByEmail(string $email): ?Customer;
    public function create(array $data): Customer;
    public function update(int $id, array $data): Customer;
    public function delete(int $id): bool;
    public function count(): int;
}

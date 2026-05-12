<?php

namespace App\Repositories\Eloquent;

use App\Models\Customer;
use App\Repositories\Contracts\CustomerRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CustomerRepository implements CustomerRepositoryInterface
{
    public function __construct(private readonly Customer $model) {}

    public function paginate(int $perPage = 20): LengthAwarePaginator
    {
        return $this->model->paginate($perPage);
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function findById(int $id): ?Customer
    {
        return $this->model->find($id);
    }

    public function findByEmail(string $email): ?Customer
    {
        return $this->model->where('email', $email)->first();
    }

    public function create(array $data): Customer
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Customer
    {
        $customer = $this->model->findOrFail($id);
        $customer->update($data);
        return $customer->fresh();
    }

    public function delete(int $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }

    public function count(): int
    {
        return $this->model->count();
    }
}

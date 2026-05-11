<?php

namespace App\Repositories\Eloquent;

use App\Models\Customer;
use App\Repositories\Contracts\CustomerRepositoryInterface;
use Illuminate\Support\Collection;

class CustomerRepository implements CustomerRepositoryInterface
{
    public function __construct(private readonly Customer $model) {}

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function findById(int $id): ?Customer
    {
        return $this->model->find($id);
    }
}

<?php

namespace App\Repositories\Contracts;

use App\Models\Customer;
use Illuminate\Support\Collection;

interface CustomerRepositoryInterface
{
    public function all(): Collection;
    public function findById(int $id): ?Customer;
}

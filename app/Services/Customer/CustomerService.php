<?php

namespace App\Services\Customer;

use App\DTOs\Customer\StoreCustomerDTO;
use App\DTOs\Customer\UpdateCustomerDTO;
use App\Models\Customer;
use App\Repositories\Contracts\CustomerRepositoryInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;

class CustomerService
{
    public function __construct(
        private readonly CustomerRepositoryInterface $customerRepository,
        private readonly OrderRepositoryInterface $orderRepository,
    ) {}

    public function store(StoreCustomerDTO $dto): Customer
    {
        return $this->customerRepository->create([
            'name'         => $dto->name,
            'email'        => $dto->email,
            'phone'        => $dto->phone,
            'address'      => $dto->address,
            'company'      => $dto->company,
            'credit_limit' => $dto->creditLimit,
        ]);
    }

    public function update(int $id, UpdateCustomerDTO $dto): Customer
    {
        return $this->customerRepository->update($id, [
            'name'         => $dto->name,
            'email'        => $dto->email,
            'phone'        => $dto->phone,
            'address'      => $dto->address,
            'company'      => $dto->company,
            'credit_limit' => $dto->creditLimit,
        ]);
    }

    public function delete(int $id): void
    {
        if ($this->orderRepository->existsByCustomerId($id)) {
            throw new \RuntimeException(__('customers.has_orders'));
        }

        $this->customerRepository->delete($id);
    }
}

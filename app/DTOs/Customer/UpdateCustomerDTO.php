<?php

namespace App\DTOs\Customer;

use App\Http\Requests\Customer\UpdateCustomerRequest;

final readonly class UpdateCustomerDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string|null $phone,
        public readonly string|null $address,
        public readonly string|null $company,
        public readonly float $creditLimit,
    ) {}

    public static function fromRequest(UpdateCustomerRequest $request): self
    {
        return new self(
            name: $request->validated('name'),
            email: $request->validated('email'),
            phone: $request->validated('phone'),
            address: $request->validated('address'),
            company: $request->validated('company'),
            creditLimit: $request->validated('credit_limit', 0),
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'company' => $this->company,
            'credit_limit' => $this->creditLimit,
        ];
    }
}

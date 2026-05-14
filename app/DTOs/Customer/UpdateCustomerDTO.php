<?php

namespace App\DTOs\Customer;

use Illuminate\Http\Request;

class UpdateCustomerDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string|null $phone,
        public readonly string|null $address,
        public readonly string|null $company,
        public readonly float $creditLimit,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            name: $request->input('name'),
            email: $request->input('email'),
            phone: $request->input('phone'),
            address: $request->input('address'),
            company: $request->input('company'),
            creditLimit: $request->input('credit_limit', 0),
        );
    }
}

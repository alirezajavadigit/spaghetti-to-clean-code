<?php

namespace App\DTOs\Order;

use Illuminate\Http\Request;

class UpdateOrderDTO
{
    public function __construct(
        public readonly int $customerId,
        public readonly string $shippingAddress,
        public readonly string|null $notes,
        public readonly string|null $dueDate,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            customerId: $request->input('customer_id'),
            shippingAddress: $request->input('shipping_address'),
            notes: $request->input('notes'),
            dueDate: $request->input('due_date'),
        );
    }
}

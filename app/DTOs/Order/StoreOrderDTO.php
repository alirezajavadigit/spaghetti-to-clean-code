<?php

namespace App\DTOs\Order;

use Illuminate\Http\Request;

class StoreOrderDTO
{
    public function __construct(
        public readonly int $customerId,
        public readonly string $shippingAddress,
        public readonly array $products,
        public readonly string|null $notes,
        public readonly string|null $dueDate,
        public readonly mixed $attachment,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            customerId: $request->input('customer_id'),
            shippingAddress: $request->input('shipping_address'),
            products: $request->input('products', []),
            notes: $request->input('notes'),
            dueDate: $request->input('due_date'),
            attachment: $request->file('attachment'),
        );
    }
}

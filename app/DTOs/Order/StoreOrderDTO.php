<?php

namespace App\DTOs\Order;

use App\Http\Requests\Order\StoreOrderRequest;

final readonly class StoreOrderDTO
{
    public function __construct(
        public readonly int $customerId,
        public readonly string $shippingAddress,
        public readonly array $products,
        public readonly string|null $notes,
        public readonly string|null $dueDate,
        public readonly mixed $attachment,
    ) {}

    public static function fromRequest(StoreOrderRequest $request): self
    {
        return new self(
            customerId: $request->validated('customer_id'),
            shippingAddress: $request->validated('shipping_address'),
            products: $request->validated('products', []),
            notes: $request->validated('notes'),
            dueDate: $request->validated('due_date'),
            attachment: $request->file('attachment'),
        );
    }

    /**
     * Convert DTO to array format.
     */
    public function toArray(): array
    {
        return [
            'customer_id' => $this->customerId,
            'shipping_address' => $this->shippingAddress,
            'products' => $this->products,
            'notes' => $this->notes,
            'due_date' => $this->dueDate,
            'attachment' => $this->attachment,
        ];
    }
}

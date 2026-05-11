<?php

namespace App\DTOs\Order;

use App\Http\Requests\Order\UpdateOrderRequest;

final readonly class UpdateOrderDTO
{
    public function __construct(
        public readonly int $customerId,
        public readonly string $shippingAddress,
        public readonly string|null $notes,
        public readonly string|null $dueDate,
    ) {}

    public static function fromRequest(UpdateOrderRequest $request): self
    {
        return new self(
            customerId: $request->validated('customer_id'),
            shippingAddress: $request->validated('shipping_address'),
            notes: $request->validated('notes'),
            dueDate: $request->validated('due_date'),
        );
    }

    public function toArray(): array
    {
        return [
            'customer_id' => $this->customerId,
            'shipping_address' => $this->shippingAddress,
            'notes' => $this->notes,
            'due_date' => $this->dueDate,
        ];
    }
}

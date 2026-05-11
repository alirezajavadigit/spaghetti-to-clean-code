<?php

namespace App\DTOs\Product;

use App\Http\Requests\Product\UpdateProductRequest;

final readonly class UpdateProductDTO
{
    public function __construct(
        public readonly string $name,
        public readonly float $price,
        public readonly string|null $description,
        public readonly string|null $category,
        public readonly int $stock,
        public readonly bool $active,
        public readonly mixed $image,
    ) {}

    public static function fromRequest(UpdateProductRequest $request): self
    {
        return new self(
            name: $request->validated('name'),
            price: $request->validated('price'),
            description: $request->validated('description'),
            category: $request->validated('category'),
            stock: $request->validated('stock', 0),
            active: $request->boolean('active'),
            image: $request->file('image'),
        );
    }

    /**
     * Convert DTO to array format (useful for model update).
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'price' => $this->price,
            'description' => $this->description,
            'category' => $this->category,
            'stock' => $this->stock,
            'active' => $this->active,
            'image' => $this->image,
        ];
    }
}

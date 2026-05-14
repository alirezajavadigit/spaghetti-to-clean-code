<?php

namespace App\DTOs\Product;

use Illuminate\Http\Request;

class StoreProductDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $sku,
        public readonly float $price,
        public readonly string|null $description,
        public readonly string|null $category,
        public readonly int $stock,
        public readonly bool $active,
        public readonly mixed $image,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            name: $request->input('name'),
            sku: $request->input('sku'),
            price: $request->input('price'),
            description: $request->input('description'),
            category: $request->input('category'),
            stock: $request->input('stock', 0),
            active: $request->boolean('active'),
            image: $request->file('image'),
        );
    }
}

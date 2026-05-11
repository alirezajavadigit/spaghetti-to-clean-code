<?php

namespace App\Services\Product;

use App\DTOs\Product\StoreProductDTO;
use App\DTOs\Product\UpdateProductDTO;
use App\Models\Product;
use App\Repositories\Contracts\OrderItemRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ProductService
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository,
        private readonly OrderItemRepositoryInterface $orderItemRepository,
    ) {}

    public function list(string|null $query): LengthAwarePaginator|Collection
    {
        if ($query) {
            return $this->productRepository->search($query);
        }

        return $this->productRepository->paginate();
    }

    public function store(StoreProductDTO $dto): Product
    {
        return $this->productRepository->create([
            'name'        => $dto->name,
            'sku'         => $dto->sku,
            'price'       => $dto->price,
            'description' => $dto->description,
            'category'    => $dto->category,
            'stock'       => $dto->stock,
            'active'      => $dto->active,
            'image'       => $this->uploadImage($dto->image),
        ]);
    }

    public function update(int $id, UpdateProductDTO $dto): Product
    {
        $data = [
            'name'        => $dto->name,
            'price'       => $dto->price,
            'description' => $dto->description,
            'category'    => $dto->category,
            'stock'       => $dto->stock,
            'active'      => $dto->active,
        ];

        if ($dto->image) {
            $data['image'] = $this->uploadImage($dto->image);
        }

        return $this->productRepository->update($id, $data);
    }

    public function delete(int $id): void
    {
        if ($this->orderItemRepository->existsByProductId($id)) {
            throw new \RuntimeException(__('products.has_orders'));
        }

        $this->productRepository->delete($id);
    }

    private function uploadImage(mixed $file): string|null
    {
        if (!$file) return null;

        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/products'), $filename);
        return $filename;
    }
}

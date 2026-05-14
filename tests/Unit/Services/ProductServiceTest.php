<?php

namespace Tests\Unit\Services;

use App\DTOs\Product\StoreProductDTO;
use App\DTOs\Product\UpdateProductDTO;
use App\Models\Product;
use App\Repositories\Contracts\OrderItemRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Services\Product\ProductService;
use Mockery;
use Tests\TestCase;

class ProductServiceTest extends TestCase
{
    private ProductRepositoryInterface $productRepository;
    private OrderItemRepositoryInterface $orderItemRepository;
    private ProductService $productService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->productRepository   = Mockery::mock(ProductRepositoryInterface::class);
        $this->orderItemRepository = Mockery::mock(OrderItemRepositoryInterface::class);
        $this->productService      = new ProductService(
            $this->productRepository,
            $this->orderItemRepository,
        );
    }

    public function test_store_creates_product_without_image(): void
    {
        $dto = new StoreProductDTO(
            name: 'Test Widget',
            sku: 'WIDGET-001',
            price: 19.99,
            description: null,
            category: 'Widgets',
            stock: 50,
            active: true,
            image: null,
        );

        $product = new Product();

        $this->productRepository
            ->shouldReceive('create')
            ->once()
            ->with(Mockery::on(
                fn($data) =>
                $data['name'] === 'Test Widget' &&
                    $data['sku'] === 'WIDGET-001' &&
                    $data['price'] === 19.99 &&
                    $data['image'] === null
            ))
            ->andReturn($product);

        $result = $this->productService->store($dto);

        $this->assertInstanceOf(Product::class, $result);
    }

    public function test_update_updates_product_data(): void
    {
        $dto = new UpdateProductDTO(
            name: 'Updated Widget',
            price: 29.99,
            description: null,
            category: 'Widgets',
            stock: 100,
            active: true,
            image: null,
        );

        $product = new Product();

        $this->productRepository
            ->shouldReceive('update')
            ->once()
            ->with(1, Mockery::on(
                fn($data) =>
                $data['name'] === 'Updated Widget' &&
                    $data['price'] === 29.99
            ))
            ->andReturn($product);

        $result = $this->productService->update(1, $dto);

        $this->assertInstanceOf(Product::class, $result);
    }

    public function test_delete_throws_exception_when_product_has_orders(): void
    {
        $this->orderItemRepository
            ->shouldReceive('existsByProductId')
            ->once()
            ->with(1)
            ->andReturn(true);

        $this->expectException(\RuntimeException::class);

        $this->productService->delete(1);
    }

    public function test_delete_removes_product_when_no_orders(): void
    {
        $this->orderItemRepository
            ->shouldReceive('existsByProductId')
            ->once()
            ->with(1)
            ->andReturn(false);

        $this->productRepository
            ->shouldReceive('delete')
            ->once()
            ->with(1)
            ->andReturn(true);

        $this->productService->delete(1);

        $this->assertTrue(true);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}

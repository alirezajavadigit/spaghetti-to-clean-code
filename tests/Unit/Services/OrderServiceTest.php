<?php

namespace Tests\Unit\Services;

use App\DTOs\Order\UpdateOrderDTO;
use App\Models\Order;
use App\Repositories\Contracts\OrderItemRepositoryInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Services\Order\OrderService;
use Illuminate\Support\Facades\DB;
use Mockery;
use Tests\TestCase;

class OrderServiceTest extends TestCase
{
    private OrderRepositoryInterface $orderRepository;
    private OrderItemRepositoryInterface $orderItemRepository;
    private ProductRepositoryInterface $productRepository;
    private OrderService $orderService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->orderRepository     = Mockery::mock(OrderRepositoryInterface::class);
        $this->orderItemRepository = Mockery::mock(OrderItemRepositoryInterface::class);
        $this->productRepository   = Mockery::mock(ProductRepositoryInterface::class);
        $this->orderService        = new OrderService(
            $this->orderRepository,
            $this->orderItemRepository,
            $this->productRepository,
        );
    }

    public function test_store_throws_exception_when_customer_has_pending_order(): void
    {
        $this->orderRepository
            ->shouldReceive('hasPendingForCustomer')
            ->once()
            ->with(1)
            ->andReturn(true);

        $dto = new \App\DTOs\Order\StoreOrderDTO(
            customerId: 1,
            shippingAddress: '123 Main St',
            products: [],
            notes: null,
            dueDate: null,
            attachment: null,
        );

        $this->expectException(\RuntimeException::class);

        $this->orderService->store($dto);
    }

    public function test_update_updates_order_fields(): void
    {
        $dto = new UpdateOrderDTO(
            customerId: 2,
            shippingAddress: '456 New St',
            notes: 'Updated notes',
            dueDate: null,
        );

        $order = new Order();

        $this->orderRepository
            ->shouldReceive('update')
            ->once()
            ->with(1, Mockery::on(
                fn($data) =>
                $data['customer_id'] === 2 &&
                    $data['shipping_address'] === '456 New St'
            ))
            ->andReturn($order);

        $result = $this->orderService->update(1, $dto);

        $this->assertInstanceOf(Order::class, $result);
    }

    public function test_update_status_updates_order_status(): void
    {
        $order = new Order(['status' => Order::STATUS_PROCESSING]);

        $this->orderRepository
            ->shouldReceive('update')
            ->once()
            ->with(1, ['status' => Order::STATUS_PROCESSING])
            ->andReturn($order);

        $result = $this->orderService->updateStatus(1, Order::STATUS_PROCESSING);

        $this->assertInstanceOf(Order::class, $result);
    }

    public function test_delete_removes_order_and_its_items(): void
    {
        DB::shouldReceive('transaction')
            ->once()
            ->andReturnUsing(fn($callback) => $callback());

        $this->orderItemRepository
            ->shouldReceive('deleteByOrderId')
            ->once()
            ->with(1);

        $this->orderRepository
            ->shouldReceive('delete')
            ->once()
            ->with(1)
            ->andReturn(true);

        $this->orderService->delete(1);

        $this->assertTrue(true);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}

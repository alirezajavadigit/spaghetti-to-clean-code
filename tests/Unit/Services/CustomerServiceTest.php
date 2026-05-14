<?php

namespace Tests\Unit\Services;

use App\DTOs\Customer\StoreCustomerDTO;
use App\DTOs\Customer\UpdateCustomerDTO;
use App\Models\Customer;
use App\Repositories\Contracts\CustomerRepositoryInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Services\Customer\CustomerService;
use Mockery;
use Tests\TestCase;

class CustomerServiceTest extends TestCase
{
    private CustomerRepositoryInterface $customerRepository;
    private OrderRepositoryInterface $orderRepository;
    private CustomerService $customerService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->customerRepository = Mockery::mock(CustomerRepositoryInterface::class);
        $this->orderRepository    = Mockery::mock(OrderRepositoryInterface::class);
        $this->customerService    = new CustomerService(
            $this->customerRepository,
            $this->orderRepository,
        );
    }

    public function test_store_creates_customer(): void
    {
        $dto = new StoreCustomerDTO(
            name: 'Acme Corp',
            email: 'billing@acme.com',
            phone: '555-0100',
            address: '123 Main St',
            company: 'Acme',
            creditLimit: 10000,
        );

        $customer = new Customer();

        $this->customerRepository
            ->shouldReceive('create')
            ->once()
            ->with(Mockery::on(
                fn($data) =>
                $data['name'] === 'Acme Corp' &&
                    $data['email'] === 'billing@acme.com' &&
                    $data['credit_limit'] === 10000.0
            ))
            ->andReturn($customer);

        $result = $this->customerService->store($dto);

        $this->assertInstanceOf(Customer::class, $result);
    }

    public function test_update_updates_customer(): void
    {
        $dto = new UpdateCustomerDTO(
            name: 'Updated Corp',
            email: 'new@acme.com',
            phone: null,
            address: null,
            company: null,
            creditLimit: 5000,
        );

        $customer = new Customer();

        $this->customerRepository
            ->shouldReceive('update')
            ->once()
            ->with(1, Mockery::on(
                fn($data) =>
                $data['name'] === 'Updated Corp' &&
                    $data['email'] === 'new@acme.com'
            ))
            ->andReturn($customer);

        $result = $this->customerService->update(1, $dto);

        $this->assertInstanceOf(Customer::class, $result);
    }

    public function test_delete_throws_exception_when_customer_has_orders(): void
    {
        $this->orderRepository
            ->shouldReceive('existsByCustomerId')
            ->once()
            ->with(1)
            ->andReturn(true);

        $this->expectException(\RuntimeException::class);

        $this->customerService->delete(1);
    }

    public function test_delete_removes_customer_when_no_orders(): void
    {
        $this->orderRepository
            ->shouldReceive('existsByCustomerId')
            ->once()
            ->with(1)
            ->andReturn(false);

        $this->customerRepository
            ->shouldReceive('delete')
            ->once()
            ->with(1)
            ->andReturn(true);

        $this->customerService->delete(1);

        $this->assertTrue(true);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}

<?php

namespace Tests\Unit\DTOs;

use App\DTOs\Order\StoreOrderDTO;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;

class StoreOrderDTOTest extends TestCase
{
    public function test_from_request_maps_all_fields_correctly(): void
    {
        $request = Request::create('/orders', 'POST', [
            'customer_id'      => 1,
            'shipping_address' => '123 Main St',
            'notes'            => 'Handle with care',
            'due_date'         => '2026-12-31',
            'products'         => [3 => 2, 5 => 1],
        ]);

        $dto = StoreOrderDTO::fromRequest($request);

        $this->assertSame(1, $dto->customerId);
        $this->assertSame('123 Main St', $dto->shippingAddress);
        $this->assertSame([3 => 2, 5 => 1], $dto->products);
        $this->assertSame('Handle with care', $dto->notes);
        $this->assertNull($dto->attachment);
    }
}

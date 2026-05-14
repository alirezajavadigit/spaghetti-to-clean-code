<?php

namespace Tests\Unit\DTOs;

use App\DTOs\Product\StoreProductDTO;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;

class StoreProductDTOTest extends TestCase
{
    public function test_from_request_maps_all_fields_correctly(): void
    {
        $request = Request::create('/products', 'POST', [
            'name'        => 'Test Widget',
            'sku'         => 'WIDGET-001',
            'price'       => 19.99,
            'description' => 'A test widget',
            'category'    => 'Widgets',
            'stock'       => 100,
            'active'      => true,
        ]);

        $dto = StoreProductDTO::fromRequest($request);

        $this->assertSame('Test Widget', $dto->name);
        $this->assertSame('WIDGET-001', $dto->sku);
        $this->assertSame(19.99, $dto->price);
        $this->assertSame(100, $dto->stock);
        $this->assertTrue($dto->active);
        $this->assertNull($dto->image);
    }
}

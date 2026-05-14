<?php

namespace Tests\Unit\Models;

use App\Models\Product;
use PHPUnit\Framework\TestCase;

class ProductModelTest extends TestCase
{
    public function test_formatted_price_returns_correct_format(): void
    {
        $product = new Product(['price' => 19.99]);
        $this->assertSame('$19.99', $product->formatted_price);
    }

    public function test_formatted_price_formats_large_numbers(): void
    {
        $product = new Product(['price' => 1234.5]);
        $this->assertSame('$1,234.50', $product->formatted_price);
    }

    public function test_in_stock_returns_true_when_stock_is_positive(): void
    {
        $product = new Product(['stock' => 10]);
        $this->assertTrue($product->in_stock);
    }

    public function test_in_stock_returns_false_when_stock_is_zero(): void
    {
        $product = new Product(['stock' => 0]);
        $this->assertFalse($product->in_stock);
    }
}

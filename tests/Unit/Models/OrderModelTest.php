<?php

namespace Tests\Unit\Models;

use App\Models\Order;
use PHPUnit\Framework\TestCase;

class OrderModelTest extends TestCase
{
    public function test_status_constants_have_correct_values(): void
    {
        $this->assertSame(1, Order::STATUS_PENDING);
        $this->assertSame(2, Order::STATUS_PROCESSING);
        $this->assertSame(3, Order::STATUS_SHIPPED);
        $this->assertSame(4, Order::STATUS_DELIVERED);
        $this->assertSame(5, Order::STATUS_CANCELLED);
    }

    public function test_status_label_returns_correct_label(): void
    {
        $cases = [
            Order::STATUS_PENDING    => 'Pending',
            Order::STATUS_PROCESSING => 'Processing',
            Order::STATUS_SHIPPED    => 'Shipped',
            Order::STATUS_DELIVERED  => 'Delivered',
            Order::STATUS_CANCELLED  => 'Cancelled',
        ];

        foreach ($cases as $status => $expected) {
            $order = new Order(['status' => $status]);
            $this->assertSame($expected, $order->status_label);
        }
    }

    public function test_status_label_returns_unknown_for_invalid_status(): void
    {
        $order = new Order(['status' => 99]);
        $this->assertSame('Unknown', $order->status_label);
    }

    public function test_status_color_returns_correct_color(): void
    {
        $cases = [
            Order::STATUS_PENDING    => 'secondary',
            Order::STATUS_PROCESSING => 'primary',
            Order::STATUS_SHIPPED    => 'info',
            Order::STATUS_DELIVERED  => 'success',
            Order::STATUS_CANCELLED  => 'danger',
        ];

        foreach ($cases as $status => $expected) {
            $order = new Order(['status' => $status]);
            $this->assertSame($expected, $order->status_color);
        }
    }

    public function test_status_color_returns_dark_for_invalid_status(): void
    {
        $order = new Order(['status' => 99]);
        $this->assertSame('dark', $order->status_color);
    }
}

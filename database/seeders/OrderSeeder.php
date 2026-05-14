<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $orders = [
            [
                'order_number'    => 'ORD-1001',
                'customer_id'     => 1,
                'user_id'         => 1,
                'status'          => Order::STATUS_DELIVERED,
                'subtotal'        => 124.95,
                'tax'             => 10.62,
                'total'           => 135.57,
                'shipping_address' => '123 Main St',
                'created_at'      => now()->subDays(30),
            ],
            [
                'order_number'    => 'ORD-1002',
                'customer_id'     => 2,
                'user_id'         => 1,
                'status'          => Order::STATUS_SHIPPED,
                'subtotal'        => 299.94,
                'tax'             => 25.49,
                'total'           => 325.43,
                'shipping_address' => '456 Oak Ave',
                'created_at'      => now()->subDays(15),
            ],
            [
                'order_number'    => 'ORD-1003',
                'customer_id'     => 3,
                'user_id'         => 2,
                'status'          => Order::STATUS_PROCESSING,
                'subtotal'        => 49.99,
                'tax'             => 4.25,
                'total'           => 54.24,
                'shipping_address' => '789 Pine Rd',
                'created_at'      => now()->subDays(5),
            ],
            [
                'order_number'    => 'ORD-1004',
                'customer_id'     => 1,
                'user_id'         => 2,
                'status'          => Order::STATUS_PENDING,
                'subtotal'        => 74.97,
                'tax'             => 6.37,
                'total'           => 81.34,
                'shipping_address' => '123 Main St',
                'created_at'      => now()->subDays(2),
            ],
            [
                'order_number'    => 'ORD-1005',
                'customer_id'     => 4,
                'user_id'         => 1,
                'status'          => Order::STATUS_CANCELLED,
                'subtotal'        => 199.96,
                'tax'             => 16.99,
                'total'           => 216.95,
                'shipping_address' => '321 Elm St',
                'created_at'      => now()->subDays(20),
            ],
        ];

        foreach ($orders as $order) {
            Order::create($order);
        }

        $orderItems = [
            ['order_id' => 1, 'product_id' => 1, 'quantity' => 5,  'price' => 9.99],
            ['order_id' => 1, 'product_id' => 3, 'quantity' => 1,  'price' => 49.99],
            ['order_id' => 1, 'product_id' => 5, 'quantity' => 10, 'price' => 4.99],
            ['order_id' => 2, 'product_id' => 4, 'quantity' => 3,  'price' => 99.99],
            ['order_id' => 3, 'product_id' => 3, 'quantity' => 1,  'price' => 49.99],
            ['order_id' => 4, 'product_id' => 1, 'quantity' => 3,  'price' => 9.99],
            ['order_id' => 4, 'product_id' => 6, 'quantity' => 6,  'price' => 7.49],
            ['order_id' => 5, 'product_id' => 2, 'quantity' => 4,  'price' => 24.99],
            ['order_id' => 5, 'product_id' => 5, 'quantity' => 20, 'price' => 4.99],
        ];

        foreach ($orderItems as $item) {
            OrderItem::create($item);
        }
    }
}

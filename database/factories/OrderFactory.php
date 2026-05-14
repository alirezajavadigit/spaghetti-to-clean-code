<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 20, 500);
        $tax      = round($subtotal * 0.085, 2);

        return [
            'order_number'    => 'ORD-' . strtoupper(fake()->unique()->bothify('####??')),
            'customer_id'     => Customer::factory(),
            'user_id'         => User::factory(),
            'status'          => Order::STATUS_PENDING,
            'subtotal'        => $subtotal,
            'tax'             => $tax,
            'total'           => round($subtotal + $tax, 2),
            'shipping_address' => fake()->address(),
        ];
    }

    public function pending(): static
    {
        return $this->state(['status' => Order::STATUS_PENDING]);
    }

    public function delivered(): static
    {
        return $this->state(['status' => Order::STATUS_DELIVERED]);
    }

    public function cancelled(): static
    {
        return $this->state(['status' => Order::STATUS_CANCELLED]);
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'sku'         => strtoupper(fake()->unique()->bothify('??-####')),
            'name'        => fake()->words(3, true),
            'price'       => fake()->randomFloat(2, 5, 500),
            'stock'       => fake()->numberBetween(10, 200),
            'category'    => fake()->randomElement(['Widgets', 'Gadgets', 'Parts', 'Tools']),
            'description' => fake()->sentence(),
            'active'      => true,
        ];
    }

    public function outOfStock(): static
    {
        return $this->state(['stock' => 0]);
    }

    public function inactive(): static
    {
        return $this->state(['active' => false]);
    }
}

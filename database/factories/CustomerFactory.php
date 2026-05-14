<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'         => fake()->company(),
            'email'        => fake()->unique()->safeEmail(),
            'phone'        => fake()->phoneNumber(),
            'address'      => fake()->address(),
            'company'      => fake()->company(),
            'credit_limit' => fake()->randomElement([1000, 5000, 10000]),
        ];
    }
}

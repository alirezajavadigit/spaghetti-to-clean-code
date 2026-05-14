<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'     => fake()->name(),
            'email'    => fake()->unique()->safeEmail(),
            'password' => 'password',
            'role'     => 'staff',
            'active'   => true,
        ];
    }

    public function admin(): static
    {
        return $this->state(['role' => 'admin']);
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@gmail.com',
            'password' => 'password123',
            'role'     => 'admin',
            'active'   => true,
        ]);

        User::create([
            'name'     => 'Staff Member',
            'email'    => 'staff@example.com',
            'password' => 'password123',
            'role'     => 'staff',
            'active'   => true,
        ]);
    }
}

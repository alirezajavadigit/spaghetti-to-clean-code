<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            ['name' => 'Acme Corp',    'email' => 'billing@acme.com',        'phone' => '555-0100', 'address' => '123 Main St', 'company' => 'Acme',     'credit_limit' => 10000],
            ['name' => 'Globex Inc',   'email' => 'ap@globex.com',           'phone' => '555-0101', 'address' => '456 Oak Ave', 'company' => 'Globex',   'credit_limit' => 5000],
            ['name' => 'John Smith',   'email' => 'john@smith.com',          'phone' => '555-0102', 'address' => '789 Pine Rd', 'company' => null,       'credit_limit' => 2000],
            ['name' => 'Initech',      'email' => 'orders@initech.com',      'phone' => '555-0103', 'address' => '321 Elm St',  'company' => 'Initech',  'credit_limit' => 15000],
            ['name' => 'Umbrella Ltd', 'email' => 'purchasing@umbrella.com', 'phone' => '555-0104', 'address' => '654 Maple Dr', 'company' => 'Umbrella', 'credit_limit' => 20000],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}

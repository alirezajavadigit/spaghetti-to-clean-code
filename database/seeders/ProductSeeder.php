<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['sku' => 'WIDGET-001', 'name' => 'Basic Widget',       'price' => 9.99,  'stock' => 150, 'category' => 'Widgets', 'active' => true],
            ['sku' => 'WIDGET-002', 'name' => 'Premium Widget',     'price' => 24.99, 'stock' => 75,  'category' => 'Widgets', 'active' => true],
            ['sku' => 'GADGET-001', 'name' => 'Standard Gadget',    'price' => 49.99, 'stock' => 40,  'category' => 'Gadgets', 'active' => true],
            ['sku' => 'GADGET-002', 'name' => 'Pro Gadget',         'price' => 99.99, 'stock' => 20,  'category' => 'Gadgets', 'active' => true],
            ['sku' => 'PART-001',   'name' => 'Replacement Part A', 'price' => 4.99,  'stock' => 500, 'category' => 'Parts',   'active' => true],
            ['sku' => 'PART-002',   'name' => 'Replacement Part B', 'price' => 7.49,  'stock' => 300, 'category' => 'Parts',   'active' => true],
            ['sku' => 'TOOL-001',   'name' => 'Assembly Tool',      'price' => 34.99, 'stock' => 0,   'category' => 'Tools',   'active' => false],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}

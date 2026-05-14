<?php

namespace Tests\Feature\Product;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $staff;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
        $this->staff = User::factory()->create();
    }

    public function test_staff_can_view_products_index(): void
    {
        Product::factory()->count(3)->create();

        $this->actingAs($this->staff)
            ->get(route('products.index'))
            ->assertOk()
            ->assertViewIs('products.index')
            ->assertViewHas('products');
    }

    public function test_staff_can_view_create_product_form(): void
    {
        $this->actingAs($this->staff)
            ->get(route('products.create'))
            ->assertOk()
            ->assertViewIs('products.create');
    }

    public function test_staff_can_create_a_product(): void
    {
        $this->actingAs($this->staff)
            ->post(route('products.store'), [
                'name'  => 'Test Widget',
                'sku'   => 'WIDGET-TEST',
                'price' => 19.99,
                'stock' => 100,
            ])->assertRedirect(route('products.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'name' => 'Test Widget',
            'sku'  => 'WIDGET-TEST',
        ]);
    }

    public function test_product_creation_requires_name_sku_and_price(): void
    {
        $this->actingAs($this->staff)
            ->post(route('products.store'), [])
            ->assertSessionHasErrors(['name', 'sku', 'price']);
    }

    public function test_product_sku_must_be_unique(): void
    {
        $product = Product::factory()->create(['sku' => 'EXISTING-SKU']);

        $this->actingAs($this->staff)
            ->post(route('products.store'), [
                'name'  => 'Another Product',
                'sku'   => 'EXISTING-SKU',
                'price' => 9.99,
            ])->assertSessionHasErrors(['sku']);
    }

    public function test_staff_can_update_a_product(): void
    {
        $product = Product::factory()->create();

        $this->actingAs($this->staff)
            ->put(route('products.update', $product->id), [
                'name'  => 'Updated Name',
                'price' => 29.99,
                'stock' => 50,
            ])->assertRedirect(route('products.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'id'   => $product->id,
            'name' => 'Updated Name',
        ]);
    }

    public function test_admin_can_delete_a_product_with_no_orders(): void
    {
        $product = Product::factory()->create();

        $this->actingAs($this->admin)
            ->delete(route('products.destroy', $product->id))
            ->assertRedirect(route('products.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_admin_cannot_delete_a_product_with_orders(): void
    {
        $product   = Product::factory()->create();
        $orderItem = OrderItem::factory()->create(['product_id' => $product->id]);

        $this->actingAs($this->admin)
            ->delete(route('products.destroy', $product->id))
            ->assertRedirect(route('products.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('products', ['id' => $product->id]);
    }

    public function test_staff_cannot_delete_a_product(): void
    {
        $product = Product::factory()->create();

        $this->actingAs($this->staff)
            ->delete(route('products.destroy', $product->id))
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertDatabaseHas('products', ['id' => $product->id]);
    }
}

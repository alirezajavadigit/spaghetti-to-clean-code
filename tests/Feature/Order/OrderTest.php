<?php

namespace Tests\Feature\Order;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
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

    public function test_staff_can_view_orders_index(): void
    {
        Order::factory()->count(3)->create();

        $this->actingAs($this->staff)
            ->get(route('orders.index'))
            ->assertOk()
            ->assertViewIs('orders.index');
    }

    public function test_staff_can_create_an_order(): void
    {
        $customer = Customer::factory()->create();
        $product  = Product::factory()->create(['stock' => 50, 'price' => 10.00]);

        $this->actingAs($this->staff)
            ->post(route('orders.store'), [
                'customer_id'      => $customer->id,
                'shipping_address' => '123 Test St',
                'products'         => [$product->id => 2],
            ])->assertRedirect(route('orders.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('orders', ['customer_id' => $customer->id]);
        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock' => 48]);
    }

    public function test_cannot_create_order_when_customer_has_pending_order(): void
    {
        $customer = Customer::factory()->create();
        $product  = Product::factory()->create(['stock' => 50]);

        Order::factory()->pending()->create(['customer_id' => $customer->id]);

        $this->actingAs($this->staff)
            ->post(route('orders.store'), [
                'customer_id'      => $customer->id,
                'shipping_address' => '123 Test St',
                'products'         => [$product->id => 1],
            ])->assertRedirect()
            ->assertSessionHas('error');
    }

    public function test_cannot_create_order_with_insufficient_stock(): void
    {
        $customer = Customer::factory()->create();
        $product  = Product::factory()->create(['stock' => 2]);

        $this->actingAs($this->staff)
            ->post(route('orders.store'), [
                'customer_id'      => $customer->id,
                'shipping_address' => '123 Test St',
                'products'         => [$product->id => 10],
            ])->assertRedirect()
            ->assertSessionHas('error');

        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock' => 2]);
    }

    public function test_staff_can_update_order_status(): void
    {
        $order = Order::factory()->pending()->create();

        $this->actingAs($this->staff)
            ->patch(route('orders.status', $order->id), ['status' => Order::STATUS_PROCESSING])
            ->assertRedirect(route('orders.show', $order->id))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('orders', [
            'id'     => $order->id,
            'status' => Order::STATUS_PROCESSING,
        ]);
    }

    public function test_staff_cannot_edit_delivered_order(): void
    {
        $order = Order::factory()->delivered()->create();

        $this->actingAs($this->staff)
            ->get(route('orders.edit', $order->id))
            ->assertRedirect(route('orders.show', $order->id))
            ->assertSessionHas('error');
    }

    public function test_admin_can_delete_an_order(): void
    {
        $order = Order::factory()->create();

        $this->actingAs($this->admin)
            ->delete(route('orders.destroy', $order->id))
            ->assertRedirect(route('orders.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
    }

    public function test_staff_cannot_delete_an_order(): void
    {
        $order = Order::factory()->create();

        $this->actingAs($this->staff)
            ->delete(route('orders.destroy', $order->id))
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertDatabaseHas('orders', ['id' => $order->id]);
    }

    public function test_order_creation_validates_required_fields(): void
    {
        $this->actingAs($this->staff)
            ->post(route('orders.store'), [])
            ->assertSessionHasErrors(['customer_id', 'shipping_address', 'products']);
    }

    public function test_order_search_returns_results(): void
    {
        $customer = Customer::factory()->create(['name' => 'Searchable Corp']);
        Order::factory()->create(['customer_id' => $customer->id]);

        $this->actingAs($this->staff)
            ->get(route('orders.search', ['q' => 'Searchable']))
            ->assertOk()
            ->assertViewHas('orders');
    }
}

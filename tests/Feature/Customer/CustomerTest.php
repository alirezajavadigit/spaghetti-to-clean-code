<?php

namespace Tests\Feature\Customer;

use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerTest extends TestCase
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

    public function test_staff_can_view_customers_index(): void
    {
        Customer::factory()->count(3)->create();

        $this->actingAs($this->staff)
            ->get(route('customers.index'))
            ->assertOk()
            ->assertViewIs('customers.index');
    }

    public function test_staff_can_create_a_customer(): void
    {
        $this->actingAs($this->staff)
            ->post(route('customers.store'), [
                'name'  => 'New Customer',
                'email' => 'customer@example.com',
            ])->assertRedirect(route('customers.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('customers', ['email' => 'customer@example.com']);
    }

    public function test_customer_email_must_be_unique(): void
    {
        Customer::factory()->create(['email' => 'taken@example.com']);

        $this->actingAs($this->staff)
            ->post(route('customers.store'), [
                'name'  => 'Another Customer',
                'email' => 'taken@example.com',
            ])->assertSessionHasErrors(['email']);
    }

    public function test_staff_can_update_a_customer(): void
    {
        $customer = Customer::factory()->create();

        $this->actingAs($this->staff)
            ->put(route('customers.update', $customer->id), [
                'name'  => 'Updated Name',
                'email' => $customer->email,
            ])->assertRedirect(route('customers.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('customers', [
            'id'   => $customer->id,
            'name' => 'Updated Name',
        ]);
    }

    public function test_admin_can_delete_customer_with_no_orders(): void
    {
        $customer = Customer::factory()->create();

        $this->actingAs($this->admin)
            ->delete(route('customers.destroy', $customer->id))
            ->assertRedirect(route('customers.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
    }

    public function test_admin_cannot_delete_customer_with_orders(): void
    {
        $customer = Customer::factory()->create();
        Order::factory()->create(['customer_id' => $customer->id]);

        $this->actingAs($this->admin)
            ->delete(route('customers.destroy', $customer->id))
            ->assertRedirect(route('customers.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('customers', ['id' => $customer->id]);
    }

    public function test_staff_cannot_delete_a_customer(): void
    {
        $customer = Customer::factory()->create();

        $this->actingAs($this->staff)
            ->delete(route('customers.destroy', $customer->id))
            ->assertRedirect()
            ->assertSessionHas('error');
    }

    public function test_customer_show_page_displays_orders(): void
    {
        $customer = Customer::factory()->create();
        Order::factory()->count(2)->create(['customer_id' => $customer->id]);

        $this->actingAs($this->staff)
            ->get(route('customers.show', $customer->id))
            ->assertOk()
            ->assertViewHas('orders');
    }
}

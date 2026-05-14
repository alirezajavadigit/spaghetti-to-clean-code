<?php

namespace Tests\Feature\Dashboard;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_dashboard(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('dashboard'))
            ->assertOk()
            ->assertViewIs('dashboard.index')
            ->assertViewHasAll([
                'total_revenue',
                'total_orders',
                'avg_order_value',
                'pending_count',
                'recent_orders',
                'low_stock_products',
                'top_customers',
            ]);
    }

    public function test_guest_cannot_view_dashboard(): void
    {
        $this->get(route('dashboard'))
            ->assertRedirect(route('login'));
    }
}

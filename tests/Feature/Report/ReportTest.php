<?php

namespace Tests\Feature\Report;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_reports(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('reports.index'))
            ->assertOk()
            ->assertViewIs('reports.index');
    }

    public function test_staff_cannot_view_reports(): void
    {
        $staff = User::factory()->create();

        $this->actingAs($staff)
            ->get(route('reports.index'))
            ->assertForbidden();
    }

    public function test_guest_cannot_view_reports(): void
    {
        $this->get(route('reports.index'))
            ->assertRedirect(route('login'));
    }

    public function test_admin_can_export_orders_as_csv(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('reports.export'))
            ->assertOk()
            ->assertHeader('Content-Type', 'text/csv; charset=UTF-8')
            ->assertHeader('Content-Disposition', 'attachment; filename="orders-export.csv"');
    }

    public function test_staff_cannot_export_reports(): void
    {
        $staff = User::factory()->create();

        $this->actingAs($staff)
            ->get(route('reports.export'))
            ->assertForbidden();
    }
}

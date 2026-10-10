<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTestData;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;
    use CreatesTestData;

    public function test_each_role_is_sent_to_its_own_dashboard(): void
    {
        $dashboards = [
            'admin' => 'admin.dashboard',
            'analyst' => 'analyst.dashboard',
            'project_officer' => 'project_officer.dashboard',
            'manager' => 'manager.dashboard',
        ];

        foreach ($dashboards as $role => $routeName) {
            $user = $this->userWithRole($role);

            $this->actingAs($user)
                ->get('/dashboard')
                ->assertRedirect(route($routeName));
        }
    }

    public function test_non_admin_users_cannot_open_admin_pages(): void
    {
        foreach (['analyst', 'project_officer', 'manager'] as $role) {
            $user = $this->userWithRole($role);

            $this->actingAs($user)->get(route('admin.dashboard'))->assertForbidden();
            $this->actingAs($user)->get(route('admin.users.index'))->assertForbidden();
        }
    }

    public function test_manager_cannot_create_projects_or_edit_outputs_and_risks(): void
    {
        $manager = $this->userWithRole('manager');
        $project = $this->createProject($manager);

        $this->actingAs($manager)->get(route('projects.create'))->assertForbidden();
        $this->actingAs($manager)->get(route('projects.outputs.edit', $project))->assertForbidden();
        $this->actingAs($manager)->get(route('projects.risks.edit', $project))->assertForbidden();
    }

    public function test_user_without_a_role_is_blocked(): void
    {
        $user = User::factory()->create(['role_id' => null, 'is_active' => true]);

        $this->actingAs($user)->get('/dashboard')->assertForbidden();
    }

    public function test_guest_is_redirected_to_login_from_protected_pages(): void
    {
        $this->get(route('projects.index'))->assertRedirect(route('login'));
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
    }
}
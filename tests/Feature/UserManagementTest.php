<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTestData;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;
    use CreatesTestData;

    protected function setUp(): void
    {
        parent::setUp();

        // Pages use @vite; tests have no built assets.
        $this->withoutVite();
    }

    public function test_admin_can_view_the_admin_dashboard(): void
    {
        $admin = $this->userWithRole('admin');

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertViewHas('statistics');
    }

    public function test_admin_can_create_a_user(): void
    {
        $admin = $this->userWithRole('admin');
        $analystRole = Role::firstOrCreate(['name' => 'analyst'], ['display_name' => 'Analyst']);

        $this->actingAs($admin)
            ->post(route('admin.users.store'), [
                'name' => 'New Analyst',
                'email' => 'new.analyst@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'role_id' => $analystRole->id,
            ])
            ->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', [
            'email' => 'new.analyst@example.com',
            'role_id' => $analystRole->id,
            'is_active' => true,
        ]);
    }

    public function test_create_user_rejects_duplicate_email_and_short_password(): void
    {
        $admin = $this->userWithRole('admin');

        $this->actingAs($admin)
            ->post(route('admin.users.store'), [
                'name' => 'Copy',
                'email' => $admin->email,
                'password' => 'short',
                'password_confirmation' => 'short',
                'role_id' => $admin->role_id,
            ])
            ->assertSessionHasErrors(['email', 'password']);

        $this->assertSame(1, User::count());
    }

    public function test_create_user_requires_matching_password_confirmation(): void
    {
        $admin = $this->userWithRole('admin');

        $this->actingAs($admin)
            ->post(route('admin.users.store'), [
                'name' => 'New User',
                'email' => 'new.user@example.com',
                'password' => 'password123',
                'password_confirmation' => 'different123',
                'role_id' => $admin->role_id,
            ])
            ->assertSessionHasErrors('password');

        $this->assertDatabaseMissing('users', ['email' => 'new.user@example.com']);
    }

    public function test_admin_can_edit_a_user(): void
    {
        $admin = $this->userWithRole('admin');
        $user = $this->userWithRole('analyst');
        $managerRole = Role::firstOrCreate(['name' => 'manager'], ['display_name' => 'Manager']);

        $this->actingAs($admin)
            ->put(route('admin.users.update', $user), [
                'name' => 'Renamed User',
                'email' => $user->email,
                'role_id' => $managerRole->id,
            ])
            ->assertRedirect(route('admin.users.index'));

        $user->refresh();
        $this->assertSame('Renamed User', $user->name);
        $this->assertSame($managerRole->id, $user->role_id);
    }

    public function test_admin_can_deactivate_and_activate_a_user(): void
    {
        $admin = $this->userWithRole('admin');
        $user = $this->userWithRole('analyst');

        $this->actingAs($admin)->patch(route('admin.users.status', $user));
        $this->assertFalse($user->fresh()->is_active);

        $this->actingAs($admin)->patch(route('admin.users.status', $user));
        $this->assertTrue($user->fresh()->is_active);
    }

    public function test_admin_cannot_deactivate_own_account(): void
    {
        $admin = $this->userWithRole('admin');

        $this->actingAs($admin)
            ->patch(route('admin.users.status', $admin))
            ->assertSessionHas('error');

        $this->assertTrue($admin->fresh()->is_active);
    }

    public function test_deactivated_user_cannot_login(): void
    {
        $this->userWithRole('analyst', [
            'email' => 'inactive@example.com',
            'password' => 'password123',
            'is_active' => false,
        ]);

        $this->post('/login', [
            'email' => 'inactive@example.com',
            'password' => 'password123',
        ])->assertSessionHasErrors();

        $this->assertGuest();
    }
}
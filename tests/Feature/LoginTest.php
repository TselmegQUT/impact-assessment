<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_correct_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);

        $response->assertRedirect();
    }

    public function test_user_cannot_login_with_unregistered_email(): void
    {
        $response = $this->post('/login', [
            'email' => 'unknown@example.com',
            'password' => 'password123',
        ]);

        $this->assertGuest();

        $response->assertSessionHasErrors();
    }


    public function test_login_requires_email(): void
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_login_requires_password(): void
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors('password');

        $this->assertGuest();
    }

    public function test_login_requires_valid_email_format(): void
    {
        $response = $this->post('/login', [
            'email' => 'not-an-email',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_user_cannot_login_with_wrong_password(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();

        $response->assertSessionHasErrors();
    }


    public function test_guest_cannot_access_dashboard(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    // public function test_authenticated_user_can_access_dashboard(): void
    // {
    //     $user = User::factory()->create();

    //     $response = $this->actingAs($user)
    //         ->get('/dashboard');

    //     $response->assertStatus(200);
    // }

    /* if guest is logged-out */
    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post('/logout');

        $this->assertGuest();

        $response->assertRedirect();
    }

}
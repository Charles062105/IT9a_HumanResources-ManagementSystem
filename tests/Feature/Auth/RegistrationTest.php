<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        // User should NOT be authenticated after registration
        $this->assertGuest();

        // User should be redirected to pending approval page
        $response->assertRedirect(route('auth.pending', ['email' => 'test@example.com']));

        // Verify user was created with pending status
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'status' => 'pending',
        ]);

        // Verify account activation request was created
        $this->assertDatabaseHas('user_requests', [
            'type' => 'Account Activation',
            'status' => 'pending',
        ]);
    }
}

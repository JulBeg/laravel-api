<?php

namespace Tests\Feature\Auth\Session;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SessionRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_users_can_register(): void
    {
        $response = $this->postJson('/auth/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertNoContent();
    }
}

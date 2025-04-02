<?php

declare(strict_types=1);

namespace Tests\Feature\Auth\Token;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TokenRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_users_can_register(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'token',
        ]);
    }
}

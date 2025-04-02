<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SessionTokenGenerationTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_generate_token(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->getJson('/auth/token');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'token',
                ],
            ]);

        $this->post('/auth/logout');

        $this->assertGuest();

        $this->getJson('/api/user', [
            'Authorization' => 'Bearer ' . $response->json('data.token'),
        ])->assertStatus(200);
    }

    public function test_users_can_not_generate_token_when_not_authenticated(): void
    {
        $response = $this->getJson('/auth/token');

        $response->assertStatus(401);
    }
}

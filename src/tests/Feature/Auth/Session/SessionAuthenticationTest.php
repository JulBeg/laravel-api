<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Attempting;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Auth\Events\Login;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class SessionAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Event::fake();
    }

    public function test_users_can_authenticate(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson('/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertNoContent();

        Event::assertDispatched(Attempting::class);
        Event::assertDispatched(Login::class);
        Event::assertNotDispatched(Failed::class);
        Event::assertNotDispatched(Lockout::class);
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->postJson('/auth/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();

        Event::assertDispatched(Attempting::class);
        Event::assertDispatched(Failed::class);
        Event::assertNotDispatched(Login::class);
        Event::assertNotDispatched(Lockout::class);
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/auth/logout');

        $this->assertGuest();
        $response->assertNoContent();
    }

    public function test_rate_limiting_blocks_after_max_attempts(): void
    {
        $user = User::factory()->create();
        $maxAttempts = 5;

        // First 5 attempts should not be rate limited
        RateLimiter::shouldReceive('tooManyAttempts')
            ->times($maxAttempts)
            ->andReturn(false);
        RateLimiter::shouldReceive('hit')
            ->times($maxAttempts);
        RateLimiter::shouldReceive('clear')
            ->times(0);  // No successful attempts, so clear is never called

        // Make max attempts
        for ($i = 0; $i < $maxAttempts; $i++) {
            $this->post('/auth/login', [
                'email' => $user->email,
                'password' => 'wrong-password',
            ]);
        }

        // Now mock rate limiting for the next attempt
        RateLimiter::shouldReceive('tooManyAttempts')
            ->once()
            ->andReturn(true);
        RateLimiter::shouldReceive('availableIn')
            ->once()
            ->andReturn(60);

        // Next attempt should be rate limited
        $this->postJson('/auth/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ])->assertStatus(422)
            ->assertJsonValidationErrors(['email']);

        Event::assertDispatched(Lockout::class);
    }
}

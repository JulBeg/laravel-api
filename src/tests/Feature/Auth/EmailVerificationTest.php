<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_verify_email_notification_is_sent(): void
    {
        Notification::fake();

        $user = User::factory()->unverified()->create();

        Event::dispatch(new Registered($user));

        Notification::assertSentTo($user, VerifyEmail::class);
    }

    public function test_email_can_be_verified(): void
    {
        Event::fake();

        $user = User::factory()->unverified()->create();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $this->actingAs($user)->get($verificationUrl);
        $this->assertTrue($user->fresh()->hasVerifiedEmail());

        Event::assertDispatched(Verified::class);
    }

    public function test_email_is_not_verified_with_invalid_hash(): void
    {
        Event::fake();

        $user = User::factory()->unverified()->create();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1('wrong-email')]
        );

        $this->actingAs($user)->get($verificationUrl);
        $this->assertFalse($user->fresh()->hasVerifiedEmail());

        Event::assertNotDispatched(Verified::class);
    }
}

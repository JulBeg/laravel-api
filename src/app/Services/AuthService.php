<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Auth\Events\Attempting;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Contracts\HasApiTokens;

final class AuthService
{
    private const MAX_ATTEMPTS = 5;

    public function __construct(
        private readonly Request $request,
        private readonly Dispatcher $events,
        private readonly string $name = 'token',
    ) {}

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticateWithSession(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->getCredentials(), $this->request->boolean('remember'))) {
            $this->handleFailedAuthentication();
        }

        $this->clearRateLimit();
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticateWithToken(?string $tokenName = null): string
    {
        $this->ensureIsNotRateLimited();

        $credentials = $this->getCredentials();
        $this->fireAttemptEvent($credentials);

        $validated = Auth::validate($credentials);
        $user = Auth::getLastAttempted();

        if (! $validated) {
            $this->fireFailedEvent($user, $credentials);
            $this->handleFailedAuthentication();
        }

        $this->clearRateLimit();
        $this->fireLoginEvent($user);

        return $this->createToken($user, $tokenName);
    }

    public function createToken(HasApiTokens $tokenable, ?string $tokenName): string
    {
        return $tokenable->createToken($tokenName ?? config('sanctum.default_token_name', 'auth_token'))->plainTextToken;
    }

    /**
     * @return array<string, mixed>
     */
    private function getCredentials(): array
    {
        return $this->request->only('email', 'password');
    }

    private function handleFailedAuthentication(): never
    {
        $this->hitRateLimit();

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    private function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), self::MAX_ATTEMPTS)) {
            return;
        }

        $this->events->dispatch(new Lockout($this->request));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    private function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->request->input('email')) . '|' . $this->request->ip());
    }

    private function hitRateLimit(): void
    {
        RateLimiter::hit($this->throttleKey());
    }

    private function clearRateLimit(): void
    {
        RateLimiter::clear($this->throttleKey());
    }

    /**
     * @param  array<string, mixed>  $credentials
     */
    private function fireAttemptEvent(array $credentials): void
    {
        $this->events->dispatch(new Attempting($this->name, $credentials, false));
    }

    private function fireLoginEvent(Authenticatable $user): void
    {
        $this->events->dispatch(new Login($this->name, $user, false));
    }

    /**
     * @param  array<string, mixed>  $credentials
     */
    private function fireFailedEvent(Authenticatable $user, array $credentials): void
    {
        $this->events->dispatch(new Failed($this->name, $user, $credentials));
    }
}

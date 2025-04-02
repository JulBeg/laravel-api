<?php

declare(strict_types=1);

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('local') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        VerifyEmail::createUrlUsing(function (object $notifiable) {
            $frontendVerifyUrl = config('app.frontend_url') . '/auth/verify';

            $verifyUrl = URL::temporarySignedRoute(
                'verification.verify',
                Carbon::now()->addMinutes(config('auth.verification.expire', 60)),
                [
                    'id' => $notifiable->getKey(),
                    'hash' => sha1($notifiable->getEmailForVerification()),
                ]
            );

            return $frontendVerifyUrl . '?verify_url=' . urlencode($verifyUrl);
        });

        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url') . "/password-reset/{$token}?email={$notifiable->getEmailForPasswordReset()}";
        });
    }
}

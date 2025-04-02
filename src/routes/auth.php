<?php

use App\Http\Controllers\Auth\AuthenticatedUserController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\Session\SessionAuthenticatedController;
use App\Http\Controllers\Auth\Session\SessionGeneratedTokenController;
use App\Http\Controllers\Auth\Session\SessionRegisteredController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/register', SessionRegisteredController::class)
        ->middleware('guest')
        ->name('register');

    Route::post('/login', [SessionAuthenticatedController::class, 'store'])
        ->middleware('guest')
        ->name('login');

    Route::post('/forgot-password', PasswordResetLinkController::class)
        ->middleware('guest')
        ->name('password.email');

    Route::post('/reset-password', NewPasswordController::class)
        ->middleware('guest')
        ->name('password.store');

    Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['auth', 'signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('/email/verification-notification', EmailVerificationNotificationController::class)
        ->middleware(['auth', 'throttle:6,1'])
        ->name('verification.send');

    Route::post('/logout', [SessionAuthenticatedController::class, 'destroy'])
        ->middleware('auth')
        ->name('logout');

    Route::get('/user', AuthenticatedUserController::class)
        ->middleware('auth')
        ->name('user');

    Route::get('/token', SessionGeneratedTokenController::class)
        ->middleware('auth')
        ->name('token');
});

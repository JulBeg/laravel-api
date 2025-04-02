<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth\Session;

use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class SessionAuthenticatedController
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request, AuthService $authService): Response
    {
        $authService->authenticateWithSession();

        $request->session()->regenerate();

        return response()->noContent();
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): Response
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->noContent();
    }
}

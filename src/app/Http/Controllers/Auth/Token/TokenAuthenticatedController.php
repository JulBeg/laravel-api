<?php

namespace App\Http\Controllers\Auth\Token;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TokenAuthenticatedController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request, AuthService $authService): JsonResponse
    {
        return response()->json([
            'data' => [
                'token' => $authService->authenticateWithToken($request->input('token_name')),
            ],
        ]);
    }

    /**
     * Destroy an authenticated token.
     */
    public function destroy(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(status: Response::HTTP_NO_CONTENT);
    }
}

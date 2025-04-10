<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth\Token;

use App\Http\Requests\Auth\RegisterRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class TokenRegisteredController
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function __invoke(RegisterRequest $request, UserService $userService): JsonResponse
    {
        $user = $userService->create($request->getDto());

        return response()->json([
            'token' => $user->createToken('auth_token')->plainTextToken,
        ], Response::HTTP_CREATED);
    }
}

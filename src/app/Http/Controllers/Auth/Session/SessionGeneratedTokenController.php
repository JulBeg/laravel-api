<?php

namespace App\Http\Controllers\Auth\Session;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SessionGeneratedTokenController extends Controller
{
    public function __invoke(Request $request, AuthService $authService): JsonResponse
    {
        return response()->json([
            'data' => [
                'token' => $authService->createToken($request->user(), $request->input('token_name')),
            ],
        ]);
    }
}

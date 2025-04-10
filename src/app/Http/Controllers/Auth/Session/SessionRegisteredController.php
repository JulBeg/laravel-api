<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth\Session;

use App\Http\Requests\Auth\RegisterRequest;
use App\Services\UserService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class SessionRegisteredController
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function __invoke(RegisterRequest $request, UserService $userService): Response
    {
        $user = $userService->create($request->getDto());

        Auth::login($user);

        return response()->noContent();
    }
}

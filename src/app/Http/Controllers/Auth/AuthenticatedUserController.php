<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class AuthenticatedUserController
{
    public function __invoke(Request $request): UserResource
    {
        return new UserResource($request->user());
    }
}

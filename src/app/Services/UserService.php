<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\UserDTO;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(
        private readonly Dispatcher $events,
    ) {}

    public function create(UserDTO $userDTO): User
    {
        $user = new User;
        $user->name = $userDTO->name;
        $user->email = $userDTO->email;
        $user->password = Hash::make($userDTO->password);
        $user->save();

        $this->events->dispatch(new Registered($user));

        return $user;
    }
}

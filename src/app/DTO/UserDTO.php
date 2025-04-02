<?php

namespace App\DTO;

final readonly class UserDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
    ) {}
}

<?php

namespace App\DTO;

readonly class CreateUserDTO
{
    public function __construct(
        public string $email,
        public string $password,
        public string $name,
        public ?string $avatarPath = null,
    ) {
    }
}
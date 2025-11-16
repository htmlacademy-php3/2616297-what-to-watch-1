<?php

namespace App\DTO;

use Illuminate\Http\UploadedFile;

readonly class RegisterUserDTO
{
    public function __construct(
        public string $email,
        public string $password,
        public string $name,
        public ?UploadedFile $avatar = null,
    ) {
    }
}
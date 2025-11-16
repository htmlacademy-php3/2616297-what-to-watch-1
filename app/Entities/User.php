<?php

namespace App\Entities;

class User
{
    public function __construct(
        private readonly int $id,
        private string $name,
        private string $email,
        private string $password,
    ) {
    }
}
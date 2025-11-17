<?php

namespace App\Repositories;

use App\Data\RegisterUserData;

interface UserRepositoryInterface
{
    public function create(RegisterUserData $DTO): int;
}
<?php

namespace App\Repositories;

use App\DTO\CreateUserDTO;
use App\Entities\User;

interface UserRepositoryInterface
{
    public function create(CreateUserDTO $DTO): int;
}
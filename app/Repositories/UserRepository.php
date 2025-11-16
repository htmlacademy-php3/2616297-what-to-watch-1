<?php

namespace App\Repositories;

use App\DTO\CreateUserDTO;
use App\Models\User as UserModel;

class UserRepository implements UserRepositoryInterface
{
    public function create(CreateUserDTO $DTO): int
    {
        $userData = [
            'email' => $DTO->email,
            'password' => $DTO->password,
            'name' => $DTO->name,
        ];

        if (null !== $DTO->avatarPath) {
            $userData['profile_picture'] = $DTO->avatarPath;
        }

        $user = UserModel::create($userData);

        return $user->id;
    }
}
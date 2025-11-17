<?php

namespace App\Repositories;

use App\Data\RegisterUserData;
use App\Models\User as UserModel;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    public function create(RegisterUserData $DTO): int
    {
        $userData = [
            'email' => $DTO->email,
            'password' => Hash::make($DTO->password),
            'name' => $DTO->name,
            'profile_picture' => $DTO->file
        ];

        $user = UserModel::create($userData);

        return $user->id;
    }
}
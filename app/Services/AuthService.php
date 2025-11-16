<?php

namespace App\Services;

use App\DTO\CreateUserDTO;
use App\DTO\RegisterUserDTO;
use App\Exceptions\InvalidCredentialsException;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\NewAccessToken;

class AuthService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {
    }

    public function login($email, $password): NewAccessToken
    {
        if (false === Auth::attempt(
                [
                    'email' => $email,
                    'password' => $password
                ]
            )) {
            throw new InvalidCredentialsException();
        }

        return Auth::user()->createToken(env('APP_NAME', 'Laravel'));
    }

    public function register(RegisterUserDTO $DTO): NewAccessToken
    {
        $avatarPath = $DTO->avatar?->store('img/avatar');

        $id = $this->userRepository->create(
            new CreateUserDTO(
                $DTO->email,
                $DTO->password,
                $DTO->name,
                $avatarPath
            )
        );

        return User::find($id)->createToken(env('APP_NAME', 'Laravel'));
    }
}
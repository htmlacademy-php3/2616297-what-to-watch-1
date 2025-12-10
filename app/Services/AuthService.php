<?php

declare(strict_types=1);

namespace App\Services;

use App\Data\LoginUserData;
use App\Data\RegisterUserData;
use App\Exceptions\InvalidCredentialsException;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\NewAccessToken;

/**
 * Сервис для аутентификации пользователя
 */
final readonly class AuthService
{
    /**
     * Внедряет зависимости
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {
    }

    /**
     * Аутентифицирует пользователя
     *
     * @param LoginUserData $DTO
     * @return NewAccessToken|null
     * @throws InvalidCredentialsException
     */
    public function login(LoginUserData $DTO): ?NewAccessToken
    {
        if (false === Auth::attempt(
                [
                    'email' => $DTO->email,
                    'password' => $DTO->password,
                ]
            )) {
            throw new InvalidCredentialsException();
        }

        return Auth::user()?->createToken(config('app.name', 'Laravel'));
    }

    /**
     * Регистрирует пользователя
     *
     * @param RegisterUserData $DTO
     * @return NewAccessToken|null
     */
    public function register(RegisterUserData $DTO): ?NewAccessToken
    {
        $id = $this->userRepository->create(
            $DTO
        );

        return User::find($id)?->createToken(config('app.name', 'Laravel'));
    }
}
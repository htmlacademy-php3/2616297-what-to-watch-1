<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Data\LoginUserData;
use App\Data\RegisterUserData;
use App\Data\UpdateUserData;
use App\Exceptions\InvalidCredentialsException;
use App\Http\Responses\BaseResponse;
use App\Http\Responses\SuccessResponse;
use App\Services\AuthService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\NewAccessToken;

/**
 * Контроллер запросов, связанных с пользователем
 *
 * @psalm-suppress UnusedClass
 */
final class UserController extends Controller
{
    /**
     * Обрабатывает запрос регистрации нового пользователя
     *
     * @param RegisterUserData $userData
     * @param AuthService $service
     * @return BaseResponse
     * @throws \ErrorException
     */
    public function register(RegisterUserData $userData, AuthService $service): BaseResponse
    {
        $token = $service->register($userData);

        if (null === $token) {
            throw new \ErrorException('Произошла ошибка при создании токена');
        }

        return new SuccessResponse(
            [
                'token' => $token->plainTextToken
            ]
        );
    }

    /**
     * Обрабатывает запрос аутентификации пользователя
     *
     * @param LoginUserData $userData
     * @param AuthService $service
     * @return BaseResponse
     * @throws InvalidCredentialsException|\ErrorException
     */
    public function login(LoginUserData $userData, AuthService $service): BaseResponse
    {
        $token = $service->login($userData);

        if (null === $token) {
            throw new \ErrorException('Произошла ошибка при создании токена');
        }

        return new SuccessResponse(
            [
                'token' => $token->plainTextToken
            ]
        );
    }

    /**
     * Обрабатывает запрос получения данных о текущем пользователе
     *
     * @param UserService $service
     * @return BaseResponse
     * @throws \Exception
     */
    public function index(UserService $service): BaseResponse
    {
        return new SuccessResponse(
            $service->getUserData(
                Auth::guard('sanctum')->user()?->id
            )
        );
    }

    /**
     * Обрабатывает запрос обновления данных о текущем пользователе
     *
     * @param UpdateUserData $data
     * @param UserService $service
     * @return BaseResponse
     */
    public function update(UpdateUserData $data, UserService $service): BaseResponse
    {
        $service->updateUserData(
            $data,
            Auth::guard('sanctum')->user()?->id
        );

        return new SuccessResponse([]);
    }

    /**
     * Обрабатывает выхода пользователя из текущего сеанса
     *
     * @param Request $request
     * @return BaseResponse
     */
    public function logout(Request $request): BaseResponse
    {
        $request->user()?->tokens()->delete();

        return new SuccessResponse(
            []
        );
    }
}
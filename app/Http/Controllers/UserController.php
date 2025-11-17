<?php

namespace App\Http\Controllers;

use App\Data\LoginUserData;
use App\Data\RegisterUserData;
use App\Http\Responses\BaseResponse;
use App\Http\Responses\SuccessResponse;
use App\Services\AuthService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function register(RegisterUserData $userData, AuthService $service): BaseResponse
    {
        $token = $service->register($userData);

        return new SuccessResponse(
            [
                'token' => $token->plainTextToken
            ]
        );
    }

    public function login(LoginUserData $userData, AuthService $service): BaseResponse
    {
        $token = $service->login($userData);

        return new SuccessResponse(
            [
                'token' => $token->plainTextToken
            ]
        );
    }

    public function index(): BaseResponse
    {
    }

    public function update(): BaseResponse
    {
    }

    public function logout(Request $request): BaseResponse
    {
        $request->user()->tokens()->delete();

        return new SuccessResponse(
            []
        );
    }
}
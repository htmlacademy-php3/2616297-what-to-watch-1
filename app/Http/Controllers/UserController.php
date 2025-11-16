<?php

namespace App\Http\Controllers;

use App\DTO\RegisterUserDTO;
use App\Http\Responses\BaseResponse;
use App\Http\Responses\SuccessResponse;
use App\Services\AuthService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function register(Request $request, AuthService $service): BaseResponse
    {
        $validated = $request->validate([
            'email' => 'required|unique:\App\Models\User',
            'password' => 'required|min:8',
            'name' => 'required|max:255',
            'file' => 'image|max:10240'
        ]);

        $token = $service->register(
            new RegisterUserDTO(
                $validated['email'],
                $validated['password'],
                $validated['name'],
                $validated['file'] ?? null
            )
        );

        return new SuccessResponse(
            [
                'token' => $token->plainTextToken
            ]
        );
    }

    public function login(Request $request, AuthService $service): BaseResponse
    {
        $validated = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $token = $service->login($validated['email'], $validated['password']);

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
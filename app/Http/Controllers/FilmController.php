<?php

namespace App\Http\Controllers;

use App\Data\FilmsRequestData;
use App\Http\Responses\BaseResponse;
use App\Http\Responses\SuccessResponse;
use App\Services\FilmService;
use Illuminate\Support\Facades\Auth;

class FilmController extends Controller
{
    public function index(FilmsRequestData $data, FilmService $service): BaseResponse
    {
        return new SuccessResponse(
            $service->getAll(
                $data,
                Auth::guard('sanctum')->user()?->hasRole('moderator') ?? false
            )
        );
    }

    public function show(int $id, FilmService $service): BaseResponse
    {
        return new SuccessResponse(
            $service->getById(
                $id,
                Auth::guard('sanctum')->user()?->id
            )
        );
    }

    public function create(): BaseResponse
    {
    }

    public function update(): BaseResponse
    {
    }
}
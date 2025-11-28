<?php

namespace App\Http\Controllers;

use App\Http\Responses\BaseResponse;
use App\Http\Responses\SuccessResponse;
use App\Services\FilmService;
use Illuminate\Support\Facades\Auth;

class SimilarController extends Controller
{
    public function index(int $id, FilmService $service): BaseResponse
    {
        return new SuccessResponse(
            $service->getSimilar(
                $id,
                Auth::guard('sanctum')->user()?->hasRole('moderator') ?? false
            )
        );
    }
}
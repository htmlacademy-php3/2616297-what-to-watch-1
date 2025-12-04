<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Film;
use App\Services\FilmService;
use App\Jobs\ProcessPendingFilm;
use Illuminate\Support\Facades\DB;
use App\Http\Responses\BaseResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Responses\SuccessResponse;

class SimilarController extends Controller
{
    public function index(int $id, FilmService $service)
    {
        return new SuccessResponse(
            $service->getSimilar(
                $id,
                Auth::guard('sanctum')->user()?->hasRole('moderator') ?? false
            )
        );
    }
}

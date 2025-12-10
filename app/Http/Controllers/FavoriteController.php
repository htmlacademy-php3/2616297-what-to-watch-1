<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Data\FavoriteFilmData;
use App\Http\Responses\BaseResponse;
use App\Http\Responses\SuccessResponse;
use App\Services\FilmService;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Контроллер фильмов в избранном
 *
 * @psalm-suppress UnusedClass
 */
final class FavoriteController extends Controller
{
    /**
     * Обрабатывает запрос получения избранных фильмов пользователя
     *
     * @param FilmService $filmService
     * @return BaseResponse
     */
    public function index(FilmService $filmService): BaseResponse
    {
        return new SuccessResponse(
            $filmService->getFavorite(
                Auth::guard('sanctum')->user()?->id
            )
        );
    }

    /**
     * Обрабатывает запрос добавления фильма в избранное
     *
     * @param FilmService $filmService
     * @param int $id
     * @return BaseResponse
     */
    public function create(FilmService $filmService, int $id): BaseResponse
    {
        $filmService->addToFavorites(
            new FavoriteFilmData(
                $id,
                (int)Auth::guard('sanctum')->id()
            )
        );

        return new SuccessResponse(
            [],
            Response::HTTP_CREATED
        );
    }

    /**
     * Обрабатывает запрос удаления фильма из избранного
     *
     * @param FilmService $filmService
     * @param int $id
     * @return BaseResponse
     */
    public function destroy(FilmService $filmService, int $id): BaseResponse
    {
        $filmService->removeFromFavorites(
            new FavoriteFilmData(
                $id,
                (int)Auth::guard('sanctum')->id()
            )
        );

        return new SuccessResponse(
            [],
            Response::HTTP_NO_CONTENT
        );
    }
}
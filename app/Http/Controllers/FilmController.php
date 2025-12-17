<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Data\FilmsRequestData;
use App\Data\FilmCreateData;
use App\Data\UpdateFilmData;
use App\Http\Responses\BaseResponse;
use App\Http\Responses\SuccessResponse;
use App\Services\FilmService;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Контроллер ресурса фильмов
 *
 * @psalm-suppress UnusedClass
 */
final class FilmController extends Controller
{
    /**
     * Обрабатывает запрос получения списка фильмов
     *
     * @param FilmsRequestData $data
     * @param FilmService $service
     * @return SuccessResponse
     */
    public function index(FilmsRequestData $data, FilmService $service): BaseResponse
    {
        $user = Auth::guard('sanctum')->user();

        return new SuccessResponse(
            $service->getAll(
                $data,
                $user?->id,
                $user?->hasRole('moderator') ?? false
            )
        );
    }

    /**
     * Обрабатывает запрос получения фильма по его идентификатору
     *
     * @param int $id Идентификатор фильма
     * @param FilmService $service
     * @return BaseResponse
     */
    public function show(int $id, FilmService $service): BaseResponse
    {
        return new SuccessResponse(
            $service->getById(
                $id,
                Auth::guard('sanctum')->user()?->id
            )
        );
    }

    /**
     * Обрабатывает запрос добавления нового фильма
     *
     * @param FilmCreateData $data
     * @param FilmService $service
     * @return BaseResponse
     */
    public function create(FilmCreateData $data, FilmService $service): BaseResponse
    {
        return new SuccessResponse(
            $service->createFilm(
                $data->imdbId
            ),
            Response::HTTP_CREATED
        );
    }

    /**
     * Обрабатывает запрос обновления информации по фильму
     *
     * @param UpdateFilmData $data
     * @param int $id
     * @param FilmService $service
     * @return BaseResponse
     */
    public function update(UpdateFilmData $data, int $id, FilmService $service): BaseResponse
    {
        $service->updateFilm($data, $id);

        return new SuccessResponse(
            [],
            Response::HTTP_NO_CONTENT
        );
    }
}

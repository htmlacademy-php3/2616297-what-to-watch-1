<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Data\UpdateGenreData;
use App\Http\Responses\BaseResponse;
use App\Http\Responses\SuccessResponse;
use App\Services\GenreService;
use Symfony\Component\HttpFoundation\Response;

/**
 * Контроллер ресурса жанров
 *
 * @psalm-suppress UnusedClass
 */
final class GenreController
{
    /**
     * Обрабатывает запрос получения списка жанров
     *
     * @param GenreService $genreService
     * @return BaseResponse
     */
    public function index(GenreService $genreService): BaseResponse
    {
        return new SuccessResponse(
            [
                'data' => $genreService->getAll()
            ]
        );
    }

    /**
     * Обрабатывает запрос обновления информации о жанре
     *
     * @param UpdateGenreData $data
     * @param GenreService $genreService
     * @return BaseResponse
     */
    public function update(UpdateGenreData $data, GenreService $genreService): BaseResponse
    {
        $genreService->update($data);

        return new SuccessResponse(
            [],
            Response::HTTP_NO_CONTENT
        );
    }
}
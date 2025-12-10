<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Responses\BaseResponse;
use App\Services\FilmService;
use App\Http\Responses\SuccessResponse;

/**
 * Контроллер похожих фильмов
 *
 * @psalm-suppress UnusedClass
 */
final class SimilarController extends Controller
{
    /**
     * Обрабатывает запрос получения похожих фильмов по идентификатору
     *
     * @param int $id
     * @param FilmService $service
     * @return SuccessResponse
     */
    public function index(int $id, FilmService $service): BaseResponse
    {
        return new SuccessResponse(
            $service->getSimilar(
                $id
            )
        );
    }
}

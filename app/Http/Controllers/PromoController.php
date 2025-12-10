<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Responses\BaseResponse;
use App\Http\Responses\SuccessResponse;
use App\Services\PromoService;
use Symfony\Component\HttpFoundation\Response;

/**
 * Контроллер промо фильмов
 *
 * @psalm-suppress UnusedClass
 */
final class PromoController extends Controller
{
    /**
     * Обрабатывает запрос получения текущего промо фильма
     *
     * @param PromoService $promoService
     * @return BaseResponse
     */
    public function index(PromoService $promoService): BaseResponse
    {
        return new SuccessResponse(
            [
                'data' => $promoService->getPromo()
            ]
        );
    }

    /**
     * Обрабатывает запрос установки фильма как промо
     *
     * @param int $id
     * @param PromoService $promoService
     * @return BaseResponse
     */
    public function create(int $id, PromoService $promoService): BaseResponse
    {
        $promoService->setPromo($id);

        return new SuccessResponse(
            [],
            Response::HTTP_CREATED
        );
    }
}
<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Override;
use Symfony\Component\HttpFoundation\Response;

/**
 * Базовый класс ответа
 */
abstract class BaseResponse implements Responsable
{
    /**
     * @param mixed $data
     * @param int $code
     */
    public function __construct(
        protected mixed $data,
        protected int $code = Response::HTTP_OK,
    ) {
        if ($this->data instanceof Arrayable) {
            $this->data = $this->data->toArray();
        }
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param Request $request
     * @return JsonResponse
     */
    #[Override]
    public function toResponse($request): JsonResponse
    {
        return \response()->json($this->prepareData(), $this->code);
    }

    /**
     * Преобразует данные в необходимый формат для ответа
     * @return mixed
     */
    abstract protected function prepareData(): mixed;
}
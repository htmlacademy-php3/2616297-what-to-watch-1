<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseResponse implements Responsable
{
    public function __construct(
        protected mixed $data,
        protected int $code = Response::HTTP_OK,
    ) {
        if ($this->data instanceof Arrayable) {
            $this->data = $this->data->toArray();
        }
    }

    public function toResponse($request): JsonResponse
    {
        return \response()->json($this->prepareData(), $this->code);
    }

    abstract protected function prepareData(): mixed;
}
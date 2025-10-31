<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseResponse implements Responsable
{
    protected mixed $data;

    public function __construct(
        mixed $data,
        protected int $code = Response::HTTP_OK,
    ) {
        if ($data instanceof Arrayable) {
            $data = $this->data->toArray();
        }

        $this->data = $data;
    }

    public function toResponse($request): JsonResponse
    {
        return \response()->json($this->prepareData(), $this->code);
    }

    abstract protected function prepareData(): array;
}
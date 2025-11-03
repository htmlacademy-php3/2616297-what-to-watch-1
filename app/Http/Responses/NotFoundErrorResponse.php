<?php

namespace App\Http\Responses;

use Symfony\Component\HttpFoundation\Response;

class NotFoundErrorResponse extends BaseResponse
{
    public function __construct(string $message, int $code = Response::HTTP_NOT_FOUND)
    {
        parent::__construct($message, $code);
    }

    protected function prepareData(): array
    {
        return [
            'message' => $this->data,
        ];
    }
}
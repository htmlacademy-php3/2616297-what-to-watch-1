<?php

namespace App\Http\Responses;

use Symfony\Component\HttpFoundation\Response;

class ErrorResponse extends BaseResponse
{
    public function __construct(string $message, int $code = Response::HTTP_BAD_REQUEST)
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
<?php

namespace App\Http\Responses;

use Symfony\Component\HttpFoundation\Response;

class ValidationErrorResponse extends BaseResponse
{

    public function __construct(string $message, private readonly array $errors, int $code = Response::HTTP_UNPROCESSABLE_ENTITY)
    {
        parent::__construct($message, $code);
    }

    protected function prepareData(): array
    {
        return [
            'message' => $this->data,
            'errors' => $this->errors
        ];
    }
}
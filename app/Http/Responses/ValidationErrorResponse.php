<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Override;
use Symfony\Component\HttpFoundation\Response;

/**
 * Ответ в случае ошибки валидации
 *
 * @psalm-suppress UnusedClass
 */
final class ValidationErrorResponse extends BaseResponse
{

    /**
     * @param string $message
     * @param array $errors
     * @param int $code
     */
    public function __construct(string $message, private readonly array $errors, int $code = Response::HTTP_UNPROCESSABLE_ENTITY)
    {
        parent::__construct($message, $code);
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
    protected function prepareData(): array
    {
        return [
            'message' => $this->data,
            'errors' => $this->errors
        ];
    }
}
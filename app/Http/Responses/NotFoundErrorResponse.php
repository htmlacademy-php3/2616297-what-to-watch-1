<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Override;
use Symfony\Component\HttpFoundation\Response;

/**
 * Ответ в случае когда запрашиваемый ресурс не найден
 *
 * @psalm-suppress UnusedClass
 */
final class NotFoundErrorResponse extends BaseResponse
{
    /**
     * @param string $message
     * @param int $code
     */
    public function __construct(string $message, int $code = Response::HTTP_NOT_FOUND)
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
        ];
    }
}
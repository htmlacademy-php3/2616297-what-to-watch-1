<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Override;

/**
 * Ответ в случае успешной обработки
 */
final class SuccessResponse extends BaseResponse
{

    /**
     * {@inheritDoc}
     */
    #[Override]
    protected function prepareData(): mixed
    {
        return $this->data;
    }
}
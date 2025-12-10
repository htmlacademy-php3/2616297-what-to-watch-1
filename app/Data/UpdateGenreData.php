<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Data;

/**
 * Data-объект для обновления информации о жанре
 */
final class UpdateGenreData extends Data
{
    /**
     * @param int $id
     * @param string $name
     */
    public function __construct(
        #[FromRouteParameter('genre')]
        public int $id,
        public string $name,
    ) {
    }
}
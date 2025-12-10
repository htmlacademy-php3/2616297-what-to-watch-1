<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

/**
 * Data-объект с данными из запроса на получение списка фильмов
 *
 * @psalm-suppress PossiblyUnusedProperty
 */
final class FilmsRequestData extends Data
{
    /**
     * Список параметров запроса
     *
     * @param int|null $page
     * @param string|null $genre
     * @param string|null $status
     * @param string|null $orderBy
     * @param string|null $orderTo
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function __construct(
        public ?int $page = null,
        public ?string $genre = null,
        public ?string $status = null,
        #[MapName('order_by')]
        public ?string $orderBy = null,
        #[MapName('order_to')]
        public ?string $orderTo = null,
    ) {
    }


}
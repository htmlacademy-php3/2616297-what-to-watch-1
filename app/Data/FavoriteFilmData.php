<?php

declare(strict_types=1);

namespace App\Data;

/**
 * Data-объект для добавления фильма в избранное
 *
 * @psalm-suppress PossiblyUnusedMethod
 * @psalm-suppress PossiblyUnusedProperty
 */
final class FavoriteFilmData
{
    /**
     * @param int $filmId
     * @param int $userId
     *
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function __construct(
        public int $filmId,
        public int $userId
    ) {
    }
}
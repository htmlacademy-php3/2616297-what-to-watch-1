<?php

declare(strict_types=1);

namespace App\Data;

/**
 * Data-объект для добавления фильма в избранное
 *
 */
final class FavoriteFilmData
{
    /**
     * @param int $filmId
     * @param int $userId
     */
    public function __construct(
        public int $filmId,
        public int $userId
    ) {
    }
}
<?php

declare(strict_types=1);

namespace App\Repositories;

/**
 * Интерфейс репозитория для работы с промо фильмом
 */
interface PromoRepositoryInterface
{
    /**
     * Получить текущий промо фильм
     *
     * @return array
     */
    public function getCurrentPromo(): array;

    /**
     * Установить промо фильм
     *
     * @param int $filmId
     * @return void
     */
    public function setPromo(int $filmId): void;
}
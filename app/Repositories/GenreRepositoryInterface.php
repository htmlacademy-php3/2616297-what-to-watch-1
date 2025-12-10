<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Data\UpdateGenreData;

/**
 *  Интерфейс по работе с жанрами фильмов
 */
interface GenreRepositoryInterface
{
    /**
     * Прикрепляет фильм к жанру
     *
     * @param int $filmId
     * @param array|null $genres
     * @return void
     */
    public function attachToGenres(int $filmId, ?array $genres): void;

    /**
     * Получает все жанры
     *
     * @return array
     */
    public function getAll(): array;


    /**
     * Обновляет информацию о жанре
     *
     * @param UpdateGenreData $data
     * @return void
     */
    public function update(UpdateGenreData $data): void;
}
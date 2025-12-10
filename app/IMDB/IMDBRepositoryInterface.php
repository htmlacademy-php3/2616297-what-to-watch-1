<?php

declare(strict_types=1);

namespace App\IMDB;

use App\DTO\IMDBMovieDTO;

/**
 * Интерфейс репозитория для работы с данными о фильмах из IMDB API
 */
interface IMDBRepositoryInterface
{
    /**
     * Возвращает данные о фильме по IMDB API
     *
     * @param int $id
     * @return IMDBMovieDTO|null
     */
    public function findById(int $id): ?IMDBMovieDTO;
}

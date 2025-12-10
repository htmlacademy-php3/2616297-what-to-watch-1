<?php

declare(strict_types=1);

namespace App\DTO;

/**
 * DTO с данными о фильме в IMDB API
 */
readonly final class IMDBMovieDTO
{
    /**
     * Данные о фильме
     *
     * @param string|null $name Название
     * @param array $genres Жанры
     * @param int|null $startYear Год выпуска
     * @param string|null $description Описание
     * @param string|null $director Режиссёр
     * @param int|null $runTime Длительность
     */
    public function __construct(
        public ?string $name,
        public array $genres,
        public ?int $startYear,
        public ?string $description,
        public ?string $director,
        public ?int $runTime,
    ) {
    }
}

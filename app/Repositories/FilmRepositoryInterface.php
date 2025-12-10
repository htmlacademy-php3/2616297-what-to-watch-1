<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Data\UpdateFilmData;
use App\DTO\IMDBMovieDTO;
use App\Data\FilmsRequestData;
use App\Data\FavoriteFilmData;

/**
 * Интерфейс репозитория для работы с фильмами
 */
interface FilmRepositoryInterface
{
    /**
     * Получает все фильмы с их рейтингом
     *
     * @param FilmsRequestData $DTO
     * @param int|null $userId
     * @param bool $isModerator
     * @param int $perPage
     * @return array
     */
    public function getAllWithRating(FilmsRequestData $DTO, ?int $userId, bool $isModerator, int $perPage = 8): array;

    /**
     * Получает все фильмы по его идентификатору
     *
     * @param int $id
     * @param int|null $user_id
     * @return array
     */
    public function getById(int $id, ?int $user_id): array;

    /**
     * Получает жанр фильма
     *
     * @param int $id
     * @return string
     */
    public function getFilmGenre(int $id): string;

    /**
     * Получает похожие фильмы
     *
     * @param FilmsRequestData $data
     * @param int $id
     * @param int $perPage
     * @return array
     */
    public function getSimilar(FilmsRequestData $data, int $id, int $perPage = 8): array;

    /**
     * Добавляет новый фильм
     *
     * @param string $imdbId
     * @return int
     */
    public function create(string $imdbId): int;

    /**
     * Обновляет информацию о фильме данными из IMDB
     *
     * @param int $id
     * @param IMDBMovieDTO $DTO
     * @return void
     */
    public function updateWithIMDB(int $id, IMDBMovieDTO $DTO): void;

    /**
     * Получает фильмы, добавленные текущим пользователем в избранное
     *
     * @param int|null $userId
     * @return array
     */
    public function getFavorite(?int $userId): array;

    /**
     * Обновляет информацию о фильме
     *
     * @param UpdateFilmData $data
     * @param int $filmId
     * @return void
     */
    public function update(UpdateFilmData $data, int $filmId): void;

    /**
     * Добавляет фильм в избранное
     *
     * @param FavoriteFilmData $data
     * @return void
     */
    public function addToFavorites(FavoriteFilmData $data): void;

    /**
     * Удаляет фильм из избранного
     *
     * @param FavoriteFilmData $data
     * @return void
     */
    public function removeFromFavorites(FavoriteFilmData $data): void;
}

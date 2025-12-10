<?php

declare(strict_types=1);

namespace App\Services;

use App\Data\FilmsRequestData;
use App\Data\UpdateFilmData;
use App\Jobs\ProcessPendingFilm;
use App\IMDB\IMDBRepositoryInterface;
use App\Repositories\FilmRepositoryInterface;
use App\Repositories\GenreRepositoryInterface;
use App\Data\FavoriteFilmData;
use Exception;

/**
 * Сервис по работе с данными фильмов
 */
readonly final class FilmService
{
    /**
     * Внедряет зависимости
     *
     * @param FilmRepositoryInterface $filmRepository
     * @param IMDBRepositoryInterface $IMDBRepository
     * @param GenreRepositoryInterface $genreRepository
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function __construct(
        private FilmRepositoryInterface $filmRepository,
        private IMDBRepositoryInterface $IMDBRepository,
        private GenreRepositoryInterface $genreRepository,
    ) {
    }


    /**
     * Получает все фильмы с пагинацией
     *
     * @param FilmsRequestData $DTO
     * @param int|null $userId
     * @param bool $isModerator
     * @return array
     */
    public function getAll(FilmsRequestData $DTO, ?int $userId, bool $isModerator): array
    {
        return $this->filmRepository->getAllWithRating($DTO, $userId, $isModerator);
    }

    /**
     * Получает фильм по его идентификатору
     *
     * @param int $id
     * @param int|null $userId
     * @return array
     */
    public function getById(int $id, ?int $userId): array
    {
        return $this->filmRepository->getById($id, $userId);
    }

    /**
     * Получает похожие фильмы
     *
     * @param int $id
     * @return array
     */
    public function getSimilar(int $id): array
    {
        $genre = $this->filmRepository->getFilmGenre($id);

        $data = new FilmsRequestData(
            genre: $genre,
        );

        $returnCount = 4;

        return $this->filmRepository->getSimilar($data, $id, $returnCount);
    }

    /**
     * Добавляет новый фильм
     *
     * @param string $imdbId
     * @return int
     */
    public function createFilm(string $imdbId): int
    {
        $id = $this->filmRepository->create($imdbId);

        ProcessPendingFilm::dispatch($id);

        return $id;
    }

    /**
     * Обновляет информацию о фильмы данными из IMDB API
     *
     * @param int $id
     * @return void
     * @throws Exception
     */
    public function updateWithIMDB(int $id): void
    {
        $imdbData = $this->IMDBRepository->findById($id);

        if (null === $imdbData) {
            throw new Exception('Failed fetching IMDB data');
        }

        $this->genreRepository->attachToGenres($id, $imdbData->genres);

        $this->filmRepository->updateWithIMDB($id, $imdbData);
    }

    /**
     * Получает фильмы, добавленные пользователем в избранное
     *
     * @param int|null $userId
     * @return array
     */
    public function getFavorite(?int $userId): array
    {
        return $this->filmRepository->getFavorite($userId);
    }

    /**
     * Обновляет данные о фильме
     *
     * @param UpdateFilmData $data
     * @param int $userId
     * @return void
     */
    public function updateFilm(UpdateFilmData $data, int $userId): void
    {
        $this->filmRepository->update($data, $userId);
    }

    /**
     * Добавляет фильм в избранное
     *
     * @param FavoriteFilmData $data
     * @return void
     */
    public function addToFavorites(FavoriteFilmData $data): void
    {
        $this->filmRepository->addToFavorites($data);
    }

    /**
     * Удаляет фильм из избранного
     *
     * @param FavoriteFilmData $data
     * @return void
     */
    public function removeFromFavorites(FavoriteFilmData $data): void
    {
        $this->filmRepository->removeFromFavorites($data);
    }
}

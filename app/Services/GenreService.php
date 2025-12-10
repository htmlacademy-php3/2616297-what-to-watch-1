<?php

declare(strict_types=1);

namespace App\Services;


use App\Data\UpdateGenreData;
use App\Repositories\GenreRepositoryInterface;

/**
 * Сервис для работы с жанрами
 */
readonly final class GenreService
{

    /**
     * Внедряет зависимости
     *
     * @param GenreRepositoryInterface $genreRepository
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function __construct(
        private GenreRepositoryInterface $genreRepository,
    )
    {
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        return $this->genreRepository->getAll();
    }

    /**
     * @param UpdateGenreData $data
     * @return void
     */
    public function update(UpdateGenreData $data): void
    {
        $this->genreRepository->update($data);
    }
}
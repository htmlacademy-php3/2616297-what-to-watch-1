<?php

declare(strict_types=1);

namespace App\Services;

use App\Data\FilmsRequestData;
use App\Repositories\FilmRepositoryInterface;

class FilmService
{
    public function __construct(
        private FilmRepositoryInterface $filmRepository
    ) {
    }

    public function getAll(FilmsRequestData $DTO, bool $isAuthorized): array
    {
        return $this->filmRepository->getAllWithRating($DTO, $isAuthorized);
    }

    public function getById(int $id, ?int $userId): array
    {
        return $this->filmRepository->getById($id, $userId);
    }

    public function getSimilar(int $id, bool $isAuthorized): array
    {
        $genre = $this->filmRepository->getFilmGenre($id);

        $data = new FilmsRequestData(
            genre: $genre,
        );

        $returnCount = 4;

        return $this->filmRepository->getSimilar($data, $id, $returnCount);
    }
}
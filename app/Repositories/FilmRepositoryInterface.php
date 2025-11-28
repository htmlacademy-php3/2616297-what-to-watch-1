<?php

namespace App\Repositories;

use App\Data\FilmsRequestData;

interface FilmRepositoryInterface
{
    public function getAllWithRating(FilmsRequestData $DTO, bool $isAuthorized, int $perPage = 8): array;

    public function getById(int $id, ?int $user_id): array;

    public function getFilmGenre(int $id): string;

    public function getSimilar(FilmsRequestData $data, int $id, int $perPage = 8): array;
}
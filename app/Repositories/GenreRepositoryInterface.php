<?php

namespace App\Repositories;

interface GenreRepositoryInterface
{
    public function attachToGenre(int $filmId, string $genreName): void;
}
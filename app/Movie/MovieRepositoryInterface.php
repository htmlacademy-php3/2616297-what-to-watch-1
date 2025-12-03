<?php

declare(strict_types=1);

namespace App\Movie;

use App\DTO\IMDBMovieDTO;

interface MovieRepositoryInterface
{
    public function findById(int $id): ?IMDBMovieDTO;
}

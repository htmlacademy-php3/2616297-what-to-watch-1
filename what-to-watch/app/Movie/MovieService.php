<?php

namespace app\Movie;

final readonly class MovieService
{
    public function __construct(
        private MovieRepositoryInterface $movieRepository,
    ) {
    }

    public function findById(int $id): Movie
    {
        return $this->movieRepository->findById($id);
    }
}
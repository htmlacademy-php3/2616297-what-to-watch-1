<?php

namespace App\Repositories;

use App\Models\Film;
use App\Models\Genre;

class GenreRepository implements GenreRepositoryInterface
{
    public function attachToGenre(int $filmId, string $genreName): void
    {
        $film = Film::findOrFail($filmId);

        $genre = Genre::firstOrCreate(
            ['name' => $genreName]
        );

        $genre->films()->save($film);
    }
}
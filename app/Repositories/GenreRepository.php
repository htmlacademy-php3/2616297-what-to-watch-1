<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Data\UpdateGenreData;
use App\Models\Film;
use App\Models\Genre;
use Override;

/**
 * Реализация репозитория для работе с фильмами
 */
final class GenreRepository implements GenreRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    #[Override]
    public function attachToGenres(int $filmId, ?array $genres): void
    {
        if (null === $genres || [] === $genres) {
            return;
        }

        $genreIds = [];
        foreach ($genres as $genreName) {
            $genre = Genre::firstOrCreate(['name' => $genreName]);
            $genreIds[] = $genre->id;
        }

        $film = Film::findOrFail($filmId);
        $film->genres()->sync($genreIds);
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
    public function getAll(): array
    {
        return Genre::all()->toArray();
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
    public function update(UpdateGenreData $data): void
    {
        Genre::findOrFail($data->id)
            ->update(['name' => $data->name]);
    }
}
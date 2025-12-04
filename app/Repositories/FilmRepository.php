<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Film;
use App\Models\Genre;
use App\DTO\IMDBMovieDTO;
use App\Enums\FilmStatus;
use App\Data\FilmsRequestData;
use App\Http\Resources\FilmResource;
use Illuminate\Database\Eloquent\Builder;

class FilmRepository implements FilmRepositoryInterface
{
    public function getAllWithRating(FilmsRequestData $DTO, bool $isAuthorized, int $perPage = 8): array
    {
        return $this->queryFilms(
            $DTO->genre,
            null !== $DTO->status ? (FilmStatus::tryFrom($DTO->status) ?? FilmStatus::READY) : FilmStatus::READY,
            $DTO->orderBy ?? 'released',
            $DTO->orderTo ?? 'asc',
            $isAuthorized
        )
            ->paginate($perPage)
            ->toArray();
    }

    public function getById(int $id, ?int $user_id): array
    {
        $film = Film::select(
            [
                'id',
                'title',
                'poster_image',
                'preview_image',
                'background_image',
                'background_color',
                'released',
                'video_link',
                'preview_video_link',
            ]
        )
            ->withAvg('comments', 'rating')
            ->withCount('comments')->findOrFail($id);

        return (new FilmResource($film))->resolve();
    }

    public function getFilmGenre(int $id): string
    {
        return Film::findOrFail($id)->genre->name;
    }

    public function getSimilar(FilmsRequestData $data, int $id, int $perPage = 4): array
    {
        return $this->queryFilms(
            genre: $data->genre,
        )
            ->whereNot('id', '=', $id)
            ->paginate($perPage)
            ->toArray();
    }

    private function queryFilms(
        ?string $genre = null,
        FilmStatus $status = FilmStatus::READY,
        string $orderBy = 'released',
        string $order = 'desc',
        bool $isAuthorized = false,
    ): Builder {
        $query = Film::select(
            [
                'id',
                'title',
                'poster_image',
                'preview_image',
                'background_image',
                'background_color',
                'released',
                'video_link',
                'preview_video_link',
                'starring',
                'director',
                'run_time',
                'status'
            ]
        )
            ->addSelect(
                [
                    'genre_name' => Genre::select('name')
                        ->whereColumn('id', '=', 'films.genre_id')
                ]
            )
            ->when($genre, function ($query, $genre) {
                $query->whereHas('genre', function ($query) use ($genre) {
                    $query->whereLike('name', "%$genre%");
                });
            });

        $filmStatus = FilmStatus::READY;

        if ($isAuthorized && $status) {
            $filmStatus = $status;
        }

        $orderBy = 'rating' === $orderBy ? 'comments_avg_rating' : 'released';

        return $query->where('status', $filmStatus)
            ->withAvg('comments', 'rating')
            ->withCount('comments')
            ->orderBy(
                $orderBy,
                null !== $order ? $order : 'asc'
            );
    }

    public function create(string $imdbId): int
    {
        $film = Film::create(
            [
                'imdb_id' => $imdbId,
                'status' => FIlmStatus::PENDING
            ]
        );

        return $film->id;
    }

    public function updateWithIMDB(int $id, IMDBMovieDTO $DTO): void
    {
        $film = Film::findOrFail($id);

        $film->update(
            [
                'title' => $DTO->name,
                'released' => $DTO->startYear,
                'description' => $DTO->description,
                'director' => $DTO->director,
                'run_time' => $DTO->runTime,
                'status' => FIlmStatus::ON_MODERATION
            ]
        );
    }
}

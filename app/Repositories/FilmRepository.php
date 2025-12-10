<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Data\FavoriteFilmData;
use App\Data\FilmsRequestData;
use App\Data\UpdateFilmData;
use App\DTO\IMDBMovieDTO;
use App\Enums\FilmStatus;
use App\Http\Resources\FilmResource;
use App\Models\Film;
use App\Models\Genre;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Override;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Реализация интерфейса для работы с фильмами
 */
final class FilmRepository implements FilmRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    #[Override]
    public function getAllWithRating(FilmsRequestData $DTO, ?int $userId, bool $isModerator, int $perPage = 8): array
    {
        $queryCallback = /**
         * @psalm-return Builder<Model>
         */
            function () use ($DTO, $isModerator, $userId): Builder {
                return $this->queryFilms(
                    $DTO->genre,
                    $isModerator && $DTO->status !== null ? (FilmStatus::tryFrom($DTO->status) ??
                                                             FilmStatus::READY) : FilmStatus::READY,
                    $DTO->orderBy ?? 'released',
                    $DTO->orderTo ?? 'asc',
                    $userId
                );
            };

        if (null !== $userId) {
            /**
             * @var LengthAwarePaginator $paginator
             * @psalm-suppress UnnecessaryVarAnnotation
             */
            $paginator = $queryCallback()->paginate($perPage);
            $paginator->getCollection()->transform(fn($film) => (new FilmResource($film))->resolve());
            return $paginator->toArray();
        }

        $page = $DTO->page ?? 1;
        $cacheKey = "films_page_{$page}_genre_{$DTO->genre}_status_{$DTO->status}_sort_{$DTO->orderBy}_{$DTO->orderTo}";

        return Cache::remember($cacheKey, 1200, function () use ($DTO, $perPage) {
            /**
             * @var LengthAwarePaginator $paginator
             * @psalm-suppress UnnecessaryVarAnnotation
             */
            $paginator = $this->queryFilms(
                $DTO->genre,
                FilmStatus::READY,
                $DTO->orderBy ?? 'released',
                $DTO->orderTo ?? 'asc',
            )->paginate($perPage);

            $paginator->getCollection()->transform(fn($film) => (new FilmResource($film))->resolve());

            return $paginator->toArray();
        });
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
    public function getById(int $id, ?int $user_id): array
    {
        /** @psalm-suppress UndefinedMagicMethod */
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
            ->withExists([
                'users as is_favorite' => function (Builder $query) use ($user_id) {
                    $query->where('user_id', $user_id ?? 0);
                }
            ])
            ->withAvg('comments', 'rating')
            ->withCount('comments')->findOrFail($id);

        return (new FilmResource($film))->resolve();
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
    public function getFilmGenre(int $id): string
    {
        $film = Film::with('genres')->findOrFail($id);
        return $film->genres->first()?->name ?? '';
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
    public function getSimilar(FilmsRequestData $data, int $id, int $perPage = 4): array
    {
        /**
         * @var LengthAwarePaginator $paginator
         * @psalm-suppress UnnecessaryVarAnnotation
         */
        $paginator = $this->queryFilms(
            genre: $data->genre,
        )
            ->whereNot('id', '=', $id)
            ->paginate($perPage);

        $paginator->getCollection()->transform(fn($film) => (new FilmResource($film))->resolve());

        return $paginator->toArray();
    }


    /**
     * Собирает запрос всех фильмов
     *
     * @param string|null $genre
     * @param FilmStatus $status
     * @param string $orderBy
     * @param string $order
     * @param int|null $userId
     * @return Builder
     */
    private function queryFilms(
        ?string $genre = null,
        FilmStatus $status = FilmStatus::READY,
        string $orderBy = 'released',
        string $order = 'desc',
        ?int $userId = null,
    ): Builder {
        /** @psalm-suppress UndefinedMagicMethod */
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
            ->with('genres')
            ->withExists([
                'users as is_favorite' => function (Builder $query) use ($userId) {
                    $query->where('user_id', $userId ?? 0);
                }
            ])
            ->when($genre, function (Builder $query, string $genre) {
                $query->whereHas('genres', function (Builder $query) use ($genre) {
                    $query->where('name', $genre);
                });
            });
        $orderBy = 'rating' === $orderBy ? 'comments_avg_rating' : 'released';

        return $query->where('status', $status)
            ->withAvg('comments', 'rating')
            ->withCount('comments')
            ->orderBy(
                $orderBy,
                $order
            );
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
    public function create(string $imdbId): int
    {
        $film = Film::create(
            [
                'imdb_id' => $imdbId,
                'status' => FilmStatus::PENDING
            ]
        );

        return $film->id;
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
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
                'status' => FilmStatus::ON_MODERATION
            ]
        );
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
    public function getFavorite(?int $userId): array
    {
        $paginator = $this->queryFilms()
            ->whereHas('users', function (Builder $query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->paginate();

        return FilmResource::collection($paginator)->response()->getData(true);
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
    public function update(UpdateFilmData $data, ?int $filmId): void
    {
        if (null === $filmId) {
            throw new \Exception('User is not authorized to update film');
        }

        $film = Film::findOrFail($filmId);

        $attributes = array_filter($data->toArray());

        unset($attributes['genre']);

        $film->update($attributes);

        if ($data->genre === null) {
            return;
        }

        $genreIds = [];

        foreach ($data->genre as $genreName) {
            $genre = Genre::firstOrCreate(['name' => $genreName]);
            $genreIds[] = $genre->id;
        }

        $film->genres()->sync($genreIds);
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
    public function addToFavorites(FavoriteFilmData $data): void
    {
        $film = Film::findOrFail($data->filmId);

        if ($film->users()->where('user_id', $data->userId)->exists()) {
            throw new UnprocessableEntityHttpException('Film is already in favorites');
        }

        $film->users()->attach($data->userId);
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
    public function removeFromFavorites(FavoriteFilmData $data): void
    {
        $film = Film::findOrFail($data->filmId);

        if (!$film->users()->where('user_id', $data->userId)->exists()) {
            throw new UnprocessableEntityHttpException('Film is not in favorites');
        }

        $film->users()->detach($data->userId);
    }
}

<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Http\Resources\FilmResource;
use App\Models\Film;
use Override;

/**
 * Реализация репозитория для работы с промо фильмом
 */
final class PromoRepository implements PromoRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    #[Override]
    public function getCurrentPromo(): array
    {
        $film = Film::where('is_promo', true)->firstOrFail();

        return (new FilmResource($film))->resolve();
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
    public function setPromo(int $filmId): void
    {
        Film::where('is_promo', true)->update(['is_promo' => false]);

        Film::findOrFail($filmId)->update(['is_promo' => true]);
    }
}
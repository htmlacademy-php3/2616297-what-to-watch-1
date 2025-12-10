<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;

/**
 * Класс ресурса фильмов
 *
 * @psalm-suppress UnusedClass
 */
final class FilmResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    #[Override]
    public function toArray(Request $request): array
    {
        $result = (array) parent::toArray($request);

        $genres = $this->resource->genres->pluck('name')->toArray();
        $isFavorite = $this->resource->is_favorite ?? false;

        return array_merge(
            $result,
            [
                'genres' => $genres,
                'is_favorite' => (bool)$isFavorite,
            ]
        );
    }
}

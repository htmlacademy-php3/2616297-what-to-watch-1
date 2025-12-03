<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class FilmCreateData extends Data
{
    public function __construct(
        #[MapName('imdb_id')]
        public string $imdbId,
    ) {
    }

    public static function rules(ValidationContext $context): array
    {
        return [
            'genre' => 'string|unique:films,imdb_id',
        ];
    }
}
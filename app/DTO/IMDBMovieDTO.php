<?php

declare(strict_types=1);

namespace App\DTO;

readonly class IMDBMovieDTO
{
    public function __construct(
        public ?string $name,
        public ?string $genre,
        public ?int $startYear,
        public ?string $description,
        public ?string $director,
        public ?int $runTime,
    ) {
    }
}

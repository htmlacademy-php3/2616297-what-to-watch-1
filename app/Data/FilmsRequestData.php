<?php

namespace App\Data;

use App\Enums\FilmStatus;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class FilmsRequestData extends Data
{
    public function __construct(
        public ?int $page = null,
        public ?string $genre = null,
        public ?string $status = null,
        #[MapName('order_by')]
        public ?string $orderBy = null,
        #[MapName('order_to')]
        public ?string $orderTo = null,
    ) {
    }

    public static function rules(ValidationContext $context): array
    {
        return [
            'page' => 'integer:strict|min:1',
            'genre' => 'string|exists:genres,name',
            'status' => [Rule::in(FilmStatus::values())],
            'order_by' => [Rule::in(['rating', 'released'])],
            'order_to' => [Rule::in(['asc', 'desc'])],
        ];
    }
}
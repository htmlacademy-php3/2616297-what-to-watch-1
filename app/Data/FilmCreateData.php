<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

/**
 * Data-объект с данными из запроса на добавление нового фильма
 *
 */
final class FilmCreateData extends Data
{
    /**
     * Список параметров запроса
     *
     * @param string $imdbId
     */
    public function __construct(
        #[MapName('imdb_id')]
        public string $imdbId,
    ) {
    }

    /**
     * Список правил валидации параметров запроса
     *
     * @method static array rules(?ValidationContext $context = null)
     * @method static array messages(...$args)
     * @method static array attributes(...$args)
     * @method static bool stopOnFirstFailure()
     * @method static string redirect()
     * @method static string redirectRoute()
     * @method static string errorBag()
     */
    public static function rules(): array
    {
        return [
            'genre' => 'string|unique:films,imdb_id',
        ];
    }
}
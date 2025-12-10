<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Attributes\FromRouteParameterProperty;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

/**
 * Data-объект для обновления информации комментария
 */
final class UpdateCommentData extends Data
{
    /**
     * @param string $text
     * @param int|null $rating
     * @param int $id
     */
    public function __construct(
        public string $text,
        public ?int $rating,
        #[FromRouteParameterProperty('comment', 'id')]
        public int $id,
    ) {
    }

    /**
     * Правила валидации
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
            'text' => 'required|string|min:50|max:400',
            'rating' => 'integer|min:1|max:10',
        ];
    }
}
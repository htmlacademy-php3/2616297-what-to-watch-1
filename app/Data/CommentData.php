<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Attributes\FromAuthenticatedUserProperty;
use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Attributes\FromRouteParameterProperty;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

/**
 * Data-объект для создания комментария
 *
 */
final class CommentData extends Data
{
    /**
     * Параметры запроса
     *
     * @param string $text
     * @param int $rating
     * @param int $film_id
     * @param int|null $user_id
     * @param int|null $comment_id
     *
     */
    public function __construct(
        public string $text,
        public int $rating,
        #[FromRouteParameter('id')]
        public int $film_id,
        #[FromAuthenticatedUserProperty('sanctum','id')]
        public ?int $user_id = null,
        #[FromRouteParameterProperty('comment', 'id')]
        public ?int $comment_id = null,
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
            'rating' => 'required|integer|min:1|max:10',
            'comment_id' => 'exists:comments',
        ];
    }
}
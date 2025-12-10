<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithoutValidation;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

/**
 * Data-объект с данными из запроса на обновлении информации о пользователе
 *
 * @psalm-suppress PossiblyUnusedProperty
 */
final class UpdateUserData extends Data
{
    /**
     * Список параметров запроса
     *
     * @param string $email
     * @param string $name
     * @param string|null $password
     * @param string|null $file
     *
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function __construct(
        public string $email,
        public string $name,
        public ?string $password,
        #[WithoutValidation]
        #[WithCast(ImageCast::class)]
        #[MapName('profile_picture')]
        public ?string $file,
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
     *
     * @psalm-suppress PossiblyUnusedMethod
     */
    public static function rules(): array
    {
        return [
            'email' => ['required', 'unique:\App\Models\User'],
            'password' => ['min:8'],
            'name' => ['required', 'max:255'],
            'profile_picture' => ['image', 'max:10240'],
        ];
    }
}
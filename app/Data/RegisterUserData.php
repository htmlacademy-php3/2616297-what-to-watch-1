<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithoutValidation;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

/**
 * Data-объект с данными из запроса на регистрацию нового пользователя
 *
 */
final class RegisterUserData extends Data
{
    /**
     * Список параметров запроса
     *
     * @param string $email
     * @param string $password
     * @param string $name
     * @param string|null $file
     *
     */
    public function __construct(
        public string $email,
        public string $password,
        public string $name,
        #[WithoutValidation]
        #[WithCast(ImageCast::class)]
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
     */
    public static function rules(): array
    {
        return [
            'email' => ['required', 'unique:\App\Models\User'],
            'password' => ['required', 'min:8'],
            'name' => ['required', 'max:255'],
            'file' => ['image', 'max:10240'],
        ];
    }
}
<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Data;

/**
 * Data-объект с данными из запроса для аутентификации пользователя
 */
final class LoginUserData extends Data
{
    /**
     * Список параметров запроса
     *
     * @param string $email
     * @param string $password
     */
    public function __construct(
        public string $email,
        public string $password,
    ) {
    }
}
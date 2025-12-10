<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Перечисление статусов фильма
 */
enum FilmStatus: string
{
    case PENDING = 'pending';
    case ON_MODERATION = 'moderate';
    case READY = 'ready';

    /**
     * Преобразует перечисление в массив
     *
     * @return array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
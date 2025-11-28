<?php

namespace App\Enums;

enum FilmStatus: string
{
    case PENDING = 'pending';
    case ON_MODERATION = 'moderate';
    case READY = 'ready';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
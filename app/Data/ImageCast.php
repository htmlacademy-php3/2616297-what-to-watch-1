<?php

declare(strict_types=1);

namespace App\Data;

use Illuminate\Http\UploadedFile;
use Override;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

/**
 * Вспомогательный класс для преобразования файла запроса в файл на сервере приложения
 *
 * @psalm-suppress PossiblyUnusedProperty
 */
final class ImageCast implements Cast
{

    /**
     * Преобразует файл из запроса в файл на сервере
     *
     * @param DataProperty $property
     * @param mixed $value
     * @param array<string, mixed> $properties
     * @param CreationContext $context
     * @return false|null|string
     */
    #[Override]
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): string|false|null
    {
        if ($value instanceof UploadedFile) {
            return $value->store('avatar');
        }

        return null;
    }
}
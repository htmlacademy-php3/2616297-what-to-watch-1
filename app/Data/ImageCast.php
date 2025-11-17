<?php

namespace App\Data;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

class ImageCast implements Cast
{

    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): ?string
    {
        if ($value instanceof UploadedFile) {
            return $value->store('img/avatar');
        }

        return null;
    }
}
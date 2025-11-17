<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithoutValidation;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class RegisterUserData extends Data
{
    public function __construct(
        public string $email,
        public string $password,
        public string $name,
        #[WithoutValidation]
        #[WithCast(ImageCast::class)]
        public ?string $file,
    ) {
    }

    public static function rules(ValidationContext $context): array
    {
        return [
            'email' => ['required', 'unique:\App\Models\User'],
            'password' => ['required', 'min:8'],
            'name' => ['required', 'max:255'],
            'file' => ['image', 'max:10240'],
        ];
    }
}
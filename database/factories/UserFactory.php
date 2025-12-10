<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Override;

/**
 * Генерирует тестовые данные пользователей
 *
 * @extends Factory<User>
 * @psalm-suppress UnusedClass
 */
final class UserFactory extends Factory
{
    /** @var class-string<User> */
    protected $model = User::class;

    /**
     * {@inheritDoc}
     */
    #[Override]
    public function definition(): array
    {
        return [
            'email' => $this->faker->unique()->safeEmail(),
            'name' => $this->faker->name(),
            'password' => Hash::make('password'),
        ];
    }
}

<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Genre;
use Illuminate\Database\Eloquent\Factories\Factory;
use Override;

/**
 * Генерирует тестовые данные жанров
 *
 * @extends Factory<Genre>
 * @psalm-suppress UnusedClass
 */
final class GenreFactory extends Factory
{
    /** @var class-string<Genre> */
    protected $model = Genre::class;

    /**
     * {@inheritDoc}
     */
    #[Override]
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
        ];
    }
}
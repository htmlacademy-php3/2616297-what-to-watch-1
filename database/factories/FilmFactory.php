<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Film;
use Illuminate\Database\Eloquent\Factories\Factory;
use Override;

/**
 * Генерирует тестовые данные фильмов
 *
 * @extends Factory<Film>
 * @psalm-suppress UnusedClass
 */
final class FilmFactory extends Factory
{
    /** @var class-string<Film> */
    protected $model = Film::class;

    /**
     * {@inheritDoc}
     */
    #[Override]
    public function definition(): array
    {
        $starring = [];

        for ($i = 1; $i <= 5; $i++) {
            $starring[] = $this->faker->name;
        }

        $starring = implode(', ', $starring);

        return [
            'title' => $this->faker->word,
            'background_color' => $this->faker->hexColor,
            'video_link' => $this->faker->url,
            'preview_video_link' => $this->faker->url,
            'description' => $this->faker->paragraph,
            'director' => $this->faker->name,
            'starring' => $starring,
            'run_time' => $this->faker->numberBetween(60, 180),
            'released' => $this->faker->numberBetween(1990, date('Y')),
            'imdb_id' => 'tt0' . $this->faker->randomNumber(6, true),
        ];
    }
}
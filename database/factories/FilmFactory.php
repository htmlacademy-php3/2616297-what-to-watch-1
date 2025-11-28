<?php

namespace Database\Factories;

use App\Models\Genre;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

class FilmFactory extends Factory
{

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
<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CommentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'text' => $this->faker->paragraph(),
            'rating' => $this->faker->numberBetween(1, 10),
        ];
    }
}
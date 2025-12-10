<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Override;

/**
 * Генерирует тестовые данные комментариев
 *
 * @extends Factory<Comment>
 * @psalm-suppress UnusedClass
 */
final class CommentFactory extends Factory
{
    /** @var class-string<Comment> */
    protected $model = Comment::class;

    /**
     * {@inheritDoc}
     */
    #[Override]
    public function definition(): array
    {
        return [
            'text' => $this->faker->paragraph(),
            'rating' => $this->faker->numberBetween(1, 10),
        ];
    }
}
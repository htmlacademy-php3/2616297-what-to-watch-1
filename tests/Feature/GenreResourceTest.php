<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Film;
use App\Models\Genre;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

/**
 * Класс для тестирования ресурса жанров
 */
class GenreResourceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Создаёт пользователя с ролью модератора
     *
     * @return void
     */
    public function createModeratorRoleUser(): void
    {
        $this->seed(
            RoleSeeder::class,
        );

        $moderatorUser = User::factory()->afterCreating(function ($user) {
            $user->assignRole('moderator');
        })->create();

        $this->actingAs($moderatorUser);
    }

    /**
     * Проверят что жанры возвращаются правильно
     *
     * @return void
     */
    public function testGenresReturnsCorrectly(): void
    {
        Genre::factory()
            ->count(10)
            ->hasAttached(
                Film::factory()
            )->create();

        $this->json('GET', '/api/genres')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                    ]
                ],
            ])
            ->assertJsonCount(10, 'data');
    }

    /**
     * Проверяет что модератор может редактировать жанры
     *
     * @return void
     */
    public function testModeratorCanEditGenre(): void
    {
        Genre::factory()
            ->count(10)
            ->hasAttached(
                Film::factory()
            )->create();

        $this->createModeratorRoleUser();

        $this->json(
            'PATCH',
            '/api/genres/1',
            [
                'name' => 'Unique Genre Name'
            ]
        )
            ->assertStatus(Response::HTTP_NO_CONTENT);


        $this->assertDatabaseHas('genres', [
            'name' => 'Unique Genre Name',
        ]);
    }
}

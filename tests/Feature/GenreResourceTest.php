<?php

namespace Tests\Feature;

use App\Models\Film;
use App\Models\Genre;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class GenreResourceTest extends TestCase
{
    use RefreshDatabase;

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

    public function testGenresReturnsCorrectly(): void
    {
        Genre::factory()
            ->count(10)
            ->has(
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

    public function testModeratorCanEditGenre(): void
    {
        Genre::factory()
            ->count(10)
            ->has(
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

<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Film;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class FavoriteControllerTest extends TestCase
{
    use RefreshDatabase;

    private function authenticateUser(): User
    {
        $this->seed(
            RoleSeeder::class,
        );

        /** @var User $moderatorUser */
        $moderatorUser = User::factory()->afterCreating(function ($user) {
            $user->assignRole('moderator');
        })->create();

        $this->actingAs($moderatorUser);

        return $moderatorUser;
    }

    public function testUserCanAddFilmToFavorites(): void
    {
        $user = $this->authenticateUser();
        $film = Film::factory()->create();

        $this->postJson("/api/films/{$film->id}/favorite")
            ->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('favorite_films', [
            'user_id' => $user->id,
            'film_id' => $film->id,
        ]);
    }

    public function testCannotAddNonExistentFilmToFavorites(): void
    {
        $this->authenticateUser();
        $nonExistentFilmId = 99999;

        $this->postJson("/api/films/{$nonExistentFilmId}/favorite")
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testCannotAddDuplicateFilmToFavorites(): void
    {
        $user = $this->authenticateUser();
        $film = Film::factory()->create();

        $user->films()->attach($film->id);

        $response = $this->postJson("/api/films/{$film->id}/favorite");

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson(fn(AssertableJson $json) => $json->has('message')->etc()
            );
    }

    public function testUserCanRemoveFilmFromFavorites(): void
    {
        $user = $this->authenticateUser();
        $film = Film::factory()->create();

        $user->films()->attach($film->id);

        $this->deleteJson("/api/films/{$film->id}/favorite")
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('favorite_films', [
            'user_id' => $user->id,
            'film_id' => $film->id,
        ]);
    }

    public function testCannotRemoveNonExistentFilmFromFavorites(): void
    {
        $this->authenticateUser();
        $nonExistentFilmId = 99999;

        $response = $this->deleteJson("/api/films/{$nonExistentFilmId}/favorite");

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }


    public function testCannotRemoveFilmNotInFavorites(): void
    {
        $this->authenticateUser();
        $film = Film::factory()->create();

        $response = $this->deleteJson("/api/films/{$film->id}/favorite");

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson(fn(AssertableJson $json) => $json->has('message')->etc()
            );
    }


    public function testUnauthenticatedUserCannotManageFavorites(): void
    {
        $film = Film::factory()->create();

        $this->postJson("/api/films/{$film->id}/favorite")
            ->assertStatus(Response::HTTP_UNAUTHORIZED);

        $this->deleteJson("/api/films/{$film->id}/favorite")
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
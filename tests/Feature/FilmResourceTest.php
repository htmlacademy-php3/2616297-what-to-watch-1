<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\FilmStatus;
use App\Jobs\ProcessPendingFilm;
use App\Models\Comment;
use App\Models\Film;
use App\Models\Genre;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Testing\Fluent\AssertableJson;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class FilmResourceTest extends TestCase
{
    use RefreshDatabase;

    public function createModeratorRoleUser(): void
    {
        $this->seed(RoleSeeder::class);

        $moderatorUser = User::factory()->afterCreating(function ($user) {
            $user->assignRole('moderator');
        })->create();

        $this->actingAs($moderatorUser);
    }

    public function testIndexRouteReturnsCorrectResponse(): void
    {
        Film::factory(10)
            ->has(Genre::factory()->count(2))
            ->has(Comment::factory()->count(3))
            ->create();

        $this->json('GET', '/api/films')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'current_page',
                'data' => [
                    '*' => [
                        'title',
                        'preview_image',
                        'preview_video_link',
                        'genres',
                    ]
                ],
                'first_page_url',
                'next_page_url',
                'prev_page_url',
                'per_page',
                'total'
            ])
            ->assertJsonCount(8, 'data');
    }

    public function testAssertPaginationWorks(): void
    {
        Film::factory(10)
            ->has(Genre::factory())
            ->has(Comment::factory()->count(3))
            ->create();

        $this->json('GET', '/api/films?page=2')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonPath('prev_page_url', url('/api/films?page=1'));
    }

    public function testGenreParameterFiltersCorrectly(): void
    {
        $horror = Genre::factory()->create(['name' => 'Horror']);
        $romance = Genre::factory()->create(['name' => 'Romance']);

        Film::factory()->count(5)
            ->hasAttached($horror)
            ->create();

        Film::factory()->count(5)
            ->hasAttached($romance)
            ->create();

        $this->json('GET', '/api/films?genre=Horror')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(5, 'data')
            ->assertJson(fn(AssertableJson $json) => $json->has('data', 5)
                ->has('data.0.genres', fn($json) => $json->where('0', 'Horror')->etc())
                ->etc()
            );
    }

    public function testModeratorCanFilterFilmStatus(): void
    {
        Film::factory(10)
            ->has(Genre::factory())
            ->has(Comment::factory()->count(3))
            ->state(
                new Sequence(
                    ['status' => FilmStatus::READY->value],
                    ['status' => FilmStatus::PENDING->value]
                )
            )
            ->create();

        $this->createModeratorRoleUser();

        $this->json('GET', '/api/films?status=pending')
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(fn(AssertableJson $json) => $json
                ->has('data', 5)
                ->has(
                    'data',
                    fn(AssertableJson $json) => $json->each(
                        fn(AssertableJson $json) => $json->where('status', 'pending')->etc()
                    )
                )
                ->etc()
            );
    }

    public function testGuestUserCannotFilterPendingFilms(): void
    {
        Film::factory(10)
            ->has(Genre::factory())
            ->has(Comment::factory()->count(3))
            ->state(
                new Sequence(
                    ['status' => FilmStatus::READY->value],
                    ['status' => FilmStatus::PENDING->value]
                )
            )
            ->create();

        $this->json('GET', '/api/films?status=pending')
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(fn(AssertableJson $json) => $json
                ->has('data', 5)
                ->has(
                    'data',
                    fn(AssertableJson $json) => $json->each(
                        fn(AssertableJson $json) => $json->where('status', 'ready')->etc()
                    )
                )
                ->etc()
            );
    }

    public function testOrderBySorting(): void
    {
        Film::factory()
            ->has(Genre::factory())
            ->has(Comment::factory()->state(['rating' => 10.0])->count(3))
            ->create(['title' => 'Highest rated']);

        Film::factory()
            ->has(Genre::factory())
            ->has(Comment::factory()->state(['rating' => 9.0])->count(3))
            ->create();

        Film::factory()
            ->has(Genre::factory())
            ->has(Comment::factory()->state(['rating' => 8.0])->count(3))
            ->create(['title' => 'Lowest rated']);

        $this->json('GET', '/api/films?order_by=rating&order_to=desc')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonPath('data.0.title', 'Highest rated')
            ->assertJsonPath('data.2.title', 'Lowest rated');

        $this->json('GET', '/api/films?order_by=rating&order_to=asc')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonPath('data.0.title', 'Lowest rated')
            ->assertJsonPath('data.2.title', 'Highest rated');
    }

    public function testModelRequestReturnsCorrectValue(): void
    {
        $genre = Genre::factory()->create(['name' => 'Thriller']);

        $film = Film::factory()
            ->hasAttached($genre)
            ->create(['title' => 'The Test Film']);

        $response = $this->getJson("/api/films/{$film->id}");

        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(fn(AssertableJson $json) => $json
                ->where('id', $film->id)
                ->where('title', 'The Test Film')
                ->where('genres.0', 'Thriller')
                ->etc()
            );
    }

    public function testReturnsCorrectSimilarFilms(): void
    {
        $sharedGenre = Genre::factory()->create(['name' => 'Sci-Fi']);
        $otherGenre = Genre::factory()->create(['name' => 'Comedy']);

        $film = Film::factory()->hasAttached($sharedGenre)->create();

        Film::factory()->count(4)->hasAttached($sharedGenre)->create();

        Film::factory()->count(5)->hasAttached($otherGenre)->create();

        $this->json('GET', "/api/films/{$film->id}/similar")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(4, 'data')
            ->assertJson(fn(AssertableJson $json) => $json
                ->has(
                    'data',
                    fn(AssertableJson $json) => $json->each(
                        fn(AssertableJson $json) => $json
                            ->whereContains('genres', 'Sci-Fi')
                            ->whereNot('id', $film->id)
                            ->etc()
                    )
                )
                ->etc()
            );
    }

    public function testModeratorOnlyRoutesNotAccessible(): void
    {
        $this->json('POST', '/api/films/')
            ->assertStatus(Response::HTTP_UNAUTHORIZED);

        $this->json('PATCH', '/api/films/1')
            ->assertStatus(Response::HTTP_UNAUTHORIZED);

        $user = User::factory()->create();
        $this->actingAs($user);

        $this->json('POST', '/api/films/')
            ->assertStatus(Response::HTTP_FORBIDDEN);

        $this->json('PATCH', '/api/films/1')
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testModeratorCanPublishFilm(): void
    {
        Queue::fake();
        $this->createModeratorRoleUser();

        $this->json('POST', '/api/films/', ['imdb_id' => 'tt0482571'])
            ->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('films', [
            'imdb_id' => 'tt0482571',
            'status' => FilmStatus::PENDING->value
        ]);

        Queue::assertPushed(ProcessPendingFilm::class);
    }

    public function testModeratorCanEditFilmInfo(): void
    {
        $film = Film::factory()->create(['imdb_id' => 'tt0482571']);
        $this->createModeratorRoleUser();

        $this->json(
            'PATCH',
            "/api/films/{$film->id}",
            [
                'imdb_id' => 'tt0482571',
                'description' => 'New description.',
                'status' => FilmStatus::READY->value,
                'name' => 'The Prestige',
                'genre' => ['Drama', 'Mystery'] // Send array of strings
            ]
        )
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseHas('films', [
            'id' => $film->id,
            'description' => 'New description.',
            'title' => 'The Prestige'
        ]);

        static::assertTrue(
            $film->fresh()->genres->pluck('name')->contains('Drama')
        );
    }

    public function testUserCanSetFilmAsFavorite(): void
    {
        $film = Film::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->json('POST', '/api/films/9999/favorite')
            ->assertStatus(Response::HTTP_NOT_FOUND);

        $this->json('POST', "/api/films/{$film->id}/favorite")
            ->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('favorite_films', [
            'user_id' => $user->id,
            'film_id' => $film->id,
        ]);

        $this->json('POST', "/api/films/{$film->id}/favorite")
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->json('DELETE', "/api/films/{$film->id}/favorite")
            ->assertStatus(Response::HTTP_NO_CONTENT);
    }

    public function testUserCanGetHisFavoriteFilms(): void
    {
        $user = User::factory()->create();
        $favoriteFilms = Film::factory()->count(2)
            ->state(['status' => FilmStatus::READY->value])
            ->create();

        $user->films()->attach($favoriteFilms->pluck('id'));

        $this->actingAs($user);

        $this->json('GET', '/api/favorite')
            ->assertOk()
            ->assertJsonCount(2, 'data');
    }

    public function testModeratorCanUpdateFilm(): void
    {
        $this->createModeratorRoleUser();

        $film = Film::factory()->create([
            'imdb_id' => 'tt1234567',
            'status' => FilmStatus::ON_MODERATION,
        ]);

        $response = $this->json('PATCH', "/api/films/$film->id", [
            'name' => 'The Grand Budapest Hotel',
            'poster_image' => 'img/poster.jpg',
            'preview_image' => 'img/preview.jpg',
            'background_image' => 'img/bg.jpg',
            'background_color' => '#ffffff',
            'video_link' => 'https://example.com/video.mp4',
            'preview_video_link' => 'https://example.com/preview.mp4',
            'description' => 'Description...',
            'director' => 'Wes Anderson',
            'starring' => ['Bill Murray', 'Edward Norton'],
            'genre' => ['Comedy', 'Drama'],
            'run_time' => 99,
            'released' => 2014,
            'status' => 'ready',
        ]);

        $response->assertNoContent();

        $this->assertDatabaseHas('films', [
            'id' => $film->id,
            'title' => 'The Grand Budapest Hotel',
            'status' => 'ready',
        ]);

        $this->assertDatabaseHas('genres', ['name' => 'Comedy']);
        $this->assertDatabaseHas('genres', ['name' => 'Drama']);

        $filmGenres = $film->fresh()->genres->pluck('name')->toArray();
        $this->assertContains('Comedy', $filmGenres);
        $this->assertContains('Drama', $filmGenres);
    }

    public function testUpdateNonExistentFilmReturns404(): void
    {
        $this->createModeratorRoleUser();

        $this->json('PATCH', '/api/films/999999', [
            'name' => 'Non Existent',
            'status' => FilmStatus::READY->value,
        ])->assertNotFound();
    }
}
<?php

namespace Tests\Feature;

use App\IMDB\IMDBRepository;
use App\Jobs\ProcessPendingFilm;
use App\Models\Comment;
use App\Models\Film;
use App\Models\Genre;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Mockery;
use Mockery\MockInterface;
use Queue;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class FilmResourceTest extends TestCase
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

    /**
     * A basic feature test example.
     */
    public function testIndexRouteReturnsCorrectResponse(): void
    {
        Film::factory(10)
            ->for(Genre::factory())
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
            ->for(Genre::factory())
            ->has(Comment::factory()->count(3))
            ->create();

        $this->json('GET', '/api/films?page=2')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonPath('prev_page_url', url('/api/films?page=1'));
    }

    public function testGenreParameterFiltersCorrectly(): void
    {
        Genre::factory()
            ->count(10)->state(
                new Sequence(
                    ['name' => 'Horror'],
                    ['name' => 'Romance']
                )
            )->has(
                Film::factory()
            )->create();

        $this->json('GET', '/api/films?genre=Horror')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(5, 'data');
    }

    public function testModeratorCanFilterFilmStatus(): void
    {
        Film::factory(10)
            ->for(Genre::factory())
            ->has(Comment::factory()->count(3))
            ->state(
                new Sequence(
                    ['status' => 'ready'],
                    ['status' => 'pending']
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
            ->for(Genre::factory())
            ->has(Comment::factory()->count(3))
            ->state(
                new Sequence(
                    ['status' => 'ready'],
                    ['status' => 'pending']
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
            ->for(Genre::factory())
            ->has(
                Comment::factory()->state(
                    ['rating' => 10.0]
                )->count(3)
            )
            ->create(
                ['title' => 'Highest rated']
            );

        Film::factory()
            ->for(Genre::factory())
            ->has(
                Comment::factory()->state(
                    ['rating' => 9.0]
                )->count(3)
            )
            ->create();

        Film::factory()
            ->for(Genre::factory())
            ->has(
                Comment::factory()->state(
                    ['rating' => 8.0]
                )->count(3)
            )
            ->create(
                ['title' => 'Lowest rated']
            );

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
        Film::factory(10)
            ->for(Genre::factory())
            ->state(
                new Sequence(
                    fn(Sequence $sequence) => ['title' => 'Title ' . $sequence->index + 1],
                )
            )
            ->create();

        $response = $this->getJson('/api/films/1');

        $response
            ->assertJson(fn(AssertableJson $json) => $json->where('id', 1)
                ->where('title', 'Title 1')
                ->etc()
            );
    }

    public function testReturnsCorrectSimilarFilms(): void
    {
        Genre::factory()
            ->count(10)->state(
                new Sequence(
                    ['name' => 'Horror'],
                    ['name' => 'Romance']
                )
            )->has(
                Film::factory()
            )->create();

        $this->json('GET', '/api/films/1/similar')
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(fn(AssertableJson $json) => $json
                ->has('data', 4)
                ->has(
                    'data',
                    fn(AssertableJson $json) => $json->each(
                        fn(AssertableJson $json) => $json
                            ->where('genre_name', 'Horror')
                            ->whereNot('id', 1)
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

        $this->instance(
            IMDBRepository::class,
            Mockery::mock(IMDBRepository::class, function (MockInterface $mock) {
                $mock->shouldReceive('findById')->with(1)->once();
            })
        );

        $this->json(
            'POST',
            '/api/films/',
            [
                'imdb_id' => 'tt0482571'
            ]
        )
            ->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('films', [
            'imdb_id' => 'tt0482571',
        ]);

        Queue::assertPushed(ProcessPendingFilm::class);
    }

    public function testModeratorCanEditFilmInfo(): void
    {
        Film::factory(1)
            ->for(Genre::factory())
            ->state(
                [
                    'title' => 'Prestige',
                    'imdb_id' => 'tt0482571'
                ]
            )
            ->create();

        $this->createModeratorRoleUser();

        $this->json(
            'POST',
            '/api/films/1',
            [
                'description' => 'Rival 19th-century magicians engage in a bitter battle for trade secrets.',
            ]
        )
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseHas('films', [
            'description' => 'Rival 19th-century magicians engage in a bitter battle for trade secrets.',
        ]);
    }

    public function testUserCanSetFilmAsFavorite(): void
    {
        Genre::factory()
            ->count(10)
            ->has(
                Film::factory()
            )->create();

        $user = User::factory()->create();

        $this->actingAs($user);

        $this->json(
            'POST',
            '/api/films/2/favorite',
        )
            ->assertStatus(Response::HTTP_NOT_FOUND);

        $this->json(
            'POST',
            '/api/films/1/favorite',
        )
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseHas('favorite_films', [
            'user_id' => '1',
            'film_id' => '1',
        ]);

        $this->json(
            'POST',
            '/api/films/1/favorite',
        )
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->json(
            'DELETE',
            '/api/films/2/favorite',
        )
            ->assertStatus(Response::HTTP_NOT_FOUND);

        $this->json(
            'DELETE',
            '/api/films/1/favorite',
        )
            ->assertStatus(Response::HTTP_NO_CONTENT);
    }
}

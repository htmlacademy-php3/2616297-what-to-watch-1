<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\DTO\IMDBMovieDTO;
use App\IMDB\IMDBRepository;
use App\IMDB\IMDBRepositoryInterface;
use App\Models\Film;
use App\Models\Genre;
use App\Services\FilmService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 * @psalm-suppress UndefinedMagicMethod
 * @psalm-suppress UndefinedInterfaceMethod
 */
final class FilmServiceTest extends TestCase
{
    use RefreshDatabase;

    public function testServiceUpdatesFilmInfo(): void
    {
        $film = Film::factory()->create(['imdb_id' => 'tt0482571']);

        $this->instance(
            IMDBRepositoryInterface::class,
            Mockery::mock(IMDBRepositoryInterface::class, function ($mock) use ($film) {
                $mock->shouldReceive('findById')
                    ->with($film->id)
                    ->once()
                    ->andReturn(new IMDBMovieDTO(
                        name: 'The Prestige',
                        genres: ['Drama'],
                        startYear: 2006,
                        description: 'Magic',
                        director: 'Nolan',
                        runTime: 130
                    ));
            })
        );

        Genre::factory()->create(['name' => 'Drama']);

        $service = app(FilmService::class);
        $service->updateWithIMDB($film->id);

        $this->assertDatabaseHas('films', [
            'id' => $film->id,
            'title' => 'The Prestige',
            'released' => 2006,
        ]);

        $freshFilm = $film->fresh();
        $this->assertNotNull($freshFilm);
        $this->assertTrue($freshFilm->genres->contains('name', 'Drama'));
    }
}
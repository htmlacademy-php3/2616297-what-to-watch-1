<?php

namespace Tests\Unit;

use App\DTO\IMDBMovieDTO;
use App\IMDB\IMDBRepository;
use App\Models\Film;
use App\Models\Genre;
use App\Services\FilmService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class FilmServiceTest extends TestCase
{
    use RefreshDatabase;

    public function testServiceUpdatesFilmInfo(): void
    {
        $film = Film::factory()
            ->for(Genre::factory())
            ->create(['imdb_id' => 'tt0482571']);

        $this->instance(
            IMDBRepository::class,
            Mockery::mock(IMDBRepository::class, function (MockInterface $mock) use ($film) {
                $mock->shouldReceive('findById')
                    ->with($film->id)
                    ->once()
                    ->andReturn(
                        new IMDBMovieDTO(
                            name: 'The Prestige',
                            genre: 'Drama',
                            startYear: 2006,
                            description: 'Two stage magicians engage in competitive one-upmanship in an attempt to create the ultimate stage illusion.',
                            director: 'Christopher Nolan',
                            runTime: 130
                        )
                    );
            })
        );

        $service = app(FilmService::class);
        $service->updateWithIMDB($film->id);

        $this->assertDatabaseHas('films', [
            'id' => $film->id,
            'title' => 'The Prestige',
            'director' => 'Christopher Nolan',
            'released' => 2006,
            'description' => 'Two stage magicians engage in competitive one-upmanship in an attempt to create the ultimate stage illusion.',
            'run_time' => 130,
        ]);
    }
}

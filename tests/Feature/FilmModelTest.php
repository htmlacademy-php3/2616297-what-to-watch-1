<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Film;
use App\Models\Genre;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FilmModelTest extends TestCase
{
    use RefreshDatabase;

    public function testReturnCorrectRatingValue(): void
    {
        $film = Film::factory()
            ->for(Genre::factory())
            ->create();

        $comments = Comment::factory(3)
            ->for($film)
            ->create();

        $avgRating = round($comments->avg('rating'), 1);

        $this->assertEquals($avgRating, $film->rating);
    }
}

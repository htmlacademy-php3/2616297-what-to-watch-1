<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Film;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentModelTest extends TestCase
{
    use RefreshDatabase;

    public function testCommentNameExists(): void
    {
        $withoutUserAttached = Comment::factory()
            ->for(
                Film::factory()
                    ->has(Genre::factory())
            );

        $attachedUser = User::factory()->create();

        $withUserAttached = $withoutUserAttached->for($attachedUser);

        $this->assertEquals('Аноним', $withoutUserAttached->create()->author_name);
        $this->assertEquals($attachedUser->name, $withUserAttached->create()->author_name);
    }
}

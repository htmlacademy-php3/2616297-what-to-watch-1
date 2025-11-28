<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Film;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CommentResourceTest extends TestCase
{
    use RefreshDatabase;

    public function initDatabase(): void
    {
        Film::factory(10)
            ->for(Genre::factory())
            ->has(
                Comment::factory()
                    ->has(User::factory())
                    ->count(3)
            )
            ->create();
    }

    public function testGetCommentsByFilm(): void
    {
        $this->initDatabase();

        $this->json(
            'GET',
            '/api/comments/1'
        )
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'name',
                        'text',
                        'created_at',
                    ]
                ],
            ])
            ->assertJsonCount(3, 'data');
    }

    public function testUserCanManageComment(): void
    {
        Film::factory()
            ->for(Genre::factory())
            ->create();

        $user = User::factory()->create();

        $this->actingAs($user);

        $this->json(
            'POST',
            '/api/comments/1',
            [
                'text' => 'Not so unique text',
                'rating' => 1
            ]
        )
            ->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('comments', [
            'text' => 'Not so unique text',
            'rating' => 1
        ]);

        $this->json(
            'PATCH',
            '/api/comments/1',
            [
                'text' => 'Unique text',
                'rating' => 9
            ]
        )
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseHas('comments', [
            'text' => 'Unique text',
            'rating' => 9
        ]);

        $this->json(
            'DELETE',
            '/api/comments/1',
        )
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('comments', [
            'text' => 'Not so unique text',
        ]);
    }
}

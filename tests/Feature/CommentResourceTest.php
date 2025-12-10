<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Film;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

/**
 * Класс для тестирования ресурса комментариев
 */
class CommentResourceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Проверяет что пользователь может добавить комментарий
     *
     * @return void
     */
    public function testUserCanAddComment(): void
    {
        $user = User::factory()->create();
        $film = Film::factory()->create();

        $this->actingAs($user);

        $this->json('POST', "/api/comments/{$film->id}", [
            'text' => 'This is a great movie! This is a great movie! This is a great movie!',
            'rating' => 8,
        ])
            ->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('comments', [
            'film_id' => $film->id,
            'user_id' => $user->id,
            'rating' => 8,
        ]);
    }

    /**
     * Проверяет что пользователь может обновить свой комментарий
     *
     * @return void
     */
    public function testUserCanUpdateOwnComment(): void
    {
        $user = User::factory()->create();
        $film = Film::factory()->create();
        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'film_id' => $film->id
        ]);

        $this->actingAs($user);

        $this->json(
            'PATCH',
            "/api/comments/{$comment->id}",
            [
                'text' => 'Updated comment text Updated comment text Updated comment text',
                'rating' => 9,
            ]
        )
            ->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'text' => 'Updated comment text Updated comment text Updated comment text',
            'rating' => 9,
        ]);
    }

    /**
     * Проверяет что пользователь может удалить свой комментарий
     *
     * @return void
     */
    public function testUserCanDeleteOwnComment(): void
    {
        $user = User::factory()->create();
        $film = Film::factory()->create();
        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'film_id' => $film->id
        ]);

        $this->actingAs($user);

        $this->json('DELETE', "/api/comments/{$comment->id}")
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('comments', [
            'id' => $comment->id,
        ]);
    }

    /**
     * Проверяет что пользователь не может удалить чужой комментарий
     *
     * @return void
     */
    public function testUserCannotDeleteOthersComment(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $film = Film::factory()->create();
        $comment = Comment::factory()->create([
            'user_id' => $owner->id,
            'film_id' => $film->id
        ]);

        $this->actingAs($otherUser);

        $this->json('DELETE', "/api/comments/{$comment->id}")
            ->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
        ]);
    }

    /**
     * Проверяет что пользователь-модератор может удалить любой комментарий
     *
     * @return void
     */
    public function testModeratorCanDeleteAnyComment(): void
    {
        $this->seed(RoleSeeder::class);

        $user = User::factory()->create();
        $moderator = User::factory()->create();
        $moderator->assignRole('moderator');

        $film = Film::factory()->create();
        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'film_id' => $film->id
        ]);

        $this->actingAs($moderator);

        $this->json('DELETE', "/api/comments/{$comment->id}")
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('comments', [
            'id' => $comment->id,
        ]);
    }

    /**
     * Проверяет что пользователь может получить список комментариев по фильму
     *
     * @return void
     */
    public function testCanGetFilmComments(): void
    {
        $film = Film::factory()
            ->has(
                Comment::factory()
                    ->count(3)
            )
            ->create();

        $this->json('GET', "/api/comments/{$film->id}")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'text',
                        'rating',
                        'comment_id',
                        'user_id',
                        'created_at',
                        'updated_at',
                        'user'
                    ]
                ]
            ])
            ->assertJsonCount(3, 'data');
    }
}
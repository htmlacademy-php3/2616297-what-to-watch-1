<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Film;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class PromoResourceTest extends TestCase
{
    use RefreshDatabase;

    public function createModeratorRoleUser(): User
    {
        $this->seed(RoleSeeder::class);

        $moderatorUser = User::factory()->create();
        $moderatorUser->assignRole('moderator');

        return $moderatorUser;
    }

    public function testGetPromoFilm(): void
    {
        Film::factory()->count(1)->create(
            ['is_promo' => true]
        );

        Film::factory()->count(4)->create();

        $this->json('GET', '/api/promo')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'poster_image',
                    'released'
                ]
            ]);
    }

    public function testModeratorCanSetPromo(): void
    {
        $film = Film::factory()->create();
        $moderator = $this->createModeratorRoleUser();

        $this->actingAs($moderator);

        $this->json('POST', "/api/promo/{$film->id}")
            ->assertStatus(Response::HTTP_CREATED);
    }

    public function testOrdinaryUserCannotSetPromo(): void
    {
        $film = Film::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->json('POST', "/api/promo/{$film->id}")
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
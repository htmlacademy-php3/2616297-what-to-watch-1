<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class UserResourceTest extends TestCase
{
    use RefreshDatabase;

    public function testAuthenticatedUserCanRetrieveHisData(): void
    {
        $this->seed(
            RoleSeeder::class,
        );

        /** @var User $user */
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->json('GET', '/api/user')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'name',
                'email',
                'profile_picture',
                'role',
            ]);
    }

    public function testUserCanModifyHisData(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->json(
            'PATCH',
            '/api/user',
            [
                'email' => 'uniqueEmail@test.com',
                'name' => 'Modified name',
            ]
        )
            ->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas('users', [
            'email' => 'uniqueEmail@test.com',
            'name' => 'Modified name',
        ]);
    }
}
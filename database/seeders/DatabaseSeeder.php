<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Film;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
        ]);

        $user = User::factory()->afterCreating(function ($user) {
            if (1 === rand(0, 1)) {
                $user->assignRole('moderator');
            }
        });

        Genre::factory()
            ->count(3)
            ->has(
                Film::factory(5)
                    ->has($user)
                    ->has(Comment::factory(5)->for($user))
            )
            ->create();
    }
}

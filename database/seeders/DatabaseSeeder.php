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
        Genre::factory()
            ->has(
                Film::factory(5)
                    ->has(User::factory(1))
                    ->has(Comment::factory(5))
            )
            ->create();
    }
}

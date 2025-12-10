<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Film;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Заполняет базу данных тестовыми данными
 * @psalm-suppress UnusedClass
 */
final class DatabaseSeeder extends Seeder
{
    /**
     * @return void
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
        ]);

        /** @var Collection<int, Genre> $genres */
        $genres = Genre::factory()->count(5)->create();

        /** @var Collection<int, User> $users */
        $users = User::factory(10)
            ->create()
            ->each(function ($user) {
                if (rand(0, 1)) {
                    $user->assignRole('moderator');
                }
            });


        Film::factory(20)
            ->create()
            ->each(function ($film) use ($genres, $users) {
                $film->genres()->attach(
                    $genres->random(rand(1, 3))->pluck('id')
                );

                Comment::factory(rand(1, 5))
                    ->for($film)
                    ->for($users->random())
                    ->create();

                $film->users()->attach(
                    $users->random(rand(0, 3))->pluck('id')
                );
            });
    }
}

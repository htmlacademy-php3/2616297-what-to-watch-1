<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\User;
use App\Repositories\FilmRepository;
use App\Repositories\FilmRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(FilmRepositoryInterface::class, FilmRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        Gate::define('manage-comment', function (User $user, Comment $comment) {
            if ($user->hasRole('moderator') || $user->id === $comment->user?->id) {
                return true;
            }

            return false;
        });
    }
}

<?php

declare(strict_types=1);

use App\Http\Controllers\CommentsController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\SimilarController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [UserController::class, 'logout']);
    Route::get('/favorite', [FavoriteController::class, 'index']);
});

Route::prefix('/user')->middleware('auth:sanctum')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::patch('/', [UserController::class, 'update']);
    });

    Route::get('/', [UserController::class, 'index']);
});

Route::prefix('/films')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::middleware('role:moderator')->group(function () {
            Route::post('/', [FilmController::class, 'create']);
            Route::patch('/{id}', [FilmController::class, 'update']);
        });

        Route::post('/{id}/favorite/', [FavoriteController::class, 'create']);
        Route::delete('/{id}/favorite/', [FavoriteController::class, 'destroy']);
    });

    Route::get('/', [FilmController::class, 'index']);
    Route::get('/{id}', [FilmController::class, 'show']);
    Route::get('/{id}/similar', [SimilarController::class, 'index']);
});

Route::prefix('/genres')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::middleware('role:moderator')->group(function () {
            Route::patch('/{genre}', [GenreController::class, 'update']);
        });
    });

    Route::get('/', [GenreController::class, 'index']);
});

Route::prefix('/comments')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/{id}', [CommentsController::class, 'create']);

        Route::middleware('can:manage-comment,comment')->group(function () {
            Route::patch('/{comment}', [CommentsController::class, 'update']);
            Route::delete('/{comment}', [CommentsController::class, 'destroy']);
        });
    });

    Route::get('/{id}', [CommentsController::class, 'index']);
});

Route::prefix('/promo')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::middleware('role:moderator')->group(function () {
            Route::post('/{id}', [PromoController::class, 'create']);
        });
    });

    Route::get('/', [PromoController::class, 'index']);
});
<?php

use App\Http\Controllers\CommentsController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\SimilarController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout']);
Route::get('/favorite', [FavoriteController::class, 'index']);

Route::prefix('/user')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::patch('/', [UserController::class, 'update']);
});

Route::prefix('/films')->group(function () {
    Route::post('/{id}/favorite/', [FavoriteController::class, 'create']);
    Route::delete('/{id}/favorite/', [FavoriteController::class, 'destroy']);
    Route::get('/', [FilmController::class, 'index']);
    Route::get('/{id}', [FilmController::class, 'show']);
    Route::get('/{id}/similar', [SimilarController::class, 'index']);
    Route::post('/', [FilmController::class, 'create']);
    Route::patch('/{id}', [FilmController::class, 'update']);
});

Route::prefix('/genres')->group(function () {
    Route::get('/', [GenreController::class, 'index']);
    Route::patch('/{genre}', [GenreController::class, 'update']);
});

Route::prefix('/comments')->group(function () {
    Route::get('/{id}', [CommentsController::class, 'index']);
    Route::post('/{id}', [CommentsController::class, 'create']);
    Route::patch('/{comment}', [CommentsController::class, 'update']);
    Route::delete('/{comment}', [CommentsController::class, 'destroy']);
});

Route::prefix('/promo')->group(function () {
    Route::get('/', [PromoController::class, 'index']);
    Route::post('/{id}', [PromoController::class, 'create']);
});
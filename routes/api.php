<?php

use App\Http\Controllers\FilmController;
use App\Http\Controllers\GenreController;
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

Route::controller(UserController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::get('/user', 'about');
    Route::patch('/user', 'update');
    Route::post('/logout', 'logout');
    Route::post('/api/films/{id}/favorite/', 'setFavorite');
    Route::delete('/api/films/{id}/favorite/', 'removeFavorite');
    Route::get('/api/favorite', 'favoriteFilms');
    Route::post('/api/films/{id}/favorite/', 'setFavorite');
    Route::delete('/api/films/{id}/favorite/', 'removeFavorite');
});

Route::controller(FilmController::class)->group(function () {
    Route::get('/films', 'index');
    Route::get('/films/{id}', 'getById');
    Route::get('/films/{id}/similar', 'getSimilar');
    Route::get('/comments/{id}', 'getComments');
    Route::post('/comments/{id}', 'addComment');
    Route::patch('/comments/{comment}', 'updateComment');
    Route::delete('/comments/{comment}', 'removeComment');
    Route::get('/promo', 'getPromo');
    Route::post('/promo/{id}', 'addPromo');
    Route::post('/films', 'create');
    Route::patch('/films/{id}', 'update');
});

Route::controller(GenreController::class)->group(function () {
    Route::get('/genres', 'index');
    Route::patch('/genres/{genre}', 'update');
});
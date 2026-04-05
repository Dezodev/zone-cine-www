<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\TvShowController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\GenreController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Films
Route::prefix('films')->name('movies.')->group(function () {
    Route::get('/', [MovieController::class, 'index'])->name('index');
    Route::get('/{slug}', [MovieController::class, 'show'])->name('show');
});

// Séries
Route::prefix('series')->name('tv.')->group(function () {
    Route::get('/', [TvShowController::class, 'index'])->name('index');
    Route::get('/{slug}', [TvShowController::class, 'show'])->name('show');
});

// Personnes (acteurs, réalisateurs…)
Route::prefix('personnes')->name('people.')->group(function () {
    Route::get('/{slug}', [PersonController::class, 'show'])->name('show');
});

// Genres
Route::prefix('genre')->name('genres.')->group(function () {
    Route::get('/films/{slug}', [GenreController::class, 'movies'])->name('movies');
    Route::get('/series/{slug}', [GenreController::class, 'tvShows'])->name('tv');
});

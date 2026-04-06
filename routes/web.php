<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\TvShowController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

// Robots.txt
Route::get('/robots.txt', function () {
    $content = app()->isProduction()
        ? implode("\n", [
            'User-agent: *',
            'Allow: /',
            'Disallow: /recherche',
            '',
            'Sitemap: ' . url('sitemap.xml'),
        ])
        : implode("\n", [
            'User-agent: *',
            'Disallow: /',
        ]);

    return response($content, 200)->header('Content-Type', 'text/plain');
});

Route::get('/', [HomeController::class, 'index'])->name('home');

// Recherche
Route::get('/recherche', [SearchController::class, 'index'])->name('search');

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

// Pages légales
Route::get('/mentions-legales', [LegalController::class, 'mentions'])->name('legal.mentions');
Route::get('/politique-de-confidentialite', [LegalController::class, 'privacy'])->name('legal.privacy');

// Genres
Route::prefix('genre')->name('genres.')->group(function () {
    Route::get('/films/{slug}', [GenreController::class, 'movies'])->name('movies');
    Route::get('/series/{slug}', [GenreController::class, 'tvShows'])->name('tv');
});

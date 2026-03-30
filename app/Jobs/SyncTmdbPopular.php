<?php

namespace App\Jobs;

use App\Services\TmdbClient;
use App\Services\TmdbImporter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SyncTmdbPopular implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(
        private int $pages = 5
    ) {}

    public function handle(TmdbClient $client, TmdbImporter $importer): void
    {
        // Films populaires
        for ($page = 1; $page <= $this->pages; $page++) {
            $results = $client->popularMovies($page)['results'] ?? [];

            foreach ($results as $movie) {
                try {
                    $importer->importMovie($movie['id']);
                } catch (\Throwable $e) {
                    Log::warning("TMDB movie import failed: {$movie['id']} — {$e->getMessage()}");
                }
            }
        }

        // Séries populaires
        for ($page = 1; $page <= $this->pages; $page++) {
            $results = $client->popularTvShows($page)['results'] ?? [];

            foreach ($results as $show) {
                try {
                    $importer->importTvShow($show['id']);
                } catch (\Throwable $e) {
                    Log::warning("TMDB tv import failed: {$show['id']} — {$e->getMessage()}");
                }
            }
        }

        // Films à l'affiche et à venir (France)
        foreach (['nowPlayingMovies', 'upcomingMovies'] as $method) {
            $results = $client->$method(1)['results'] ?? [];
            foreach ($results as $movie) {
                try {
                    $importer->importMovie($movie['id']);
                } catch (\Throwable $e) {
                    Log::warning("TMDB movie import failed: {$movie['id']} — {$e->getMessage()}");
                }
            }
        }
    }
}

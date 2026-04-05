<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class TmdbClient
{
    private PendingRequest $http;

    public function __construct()
    {
        $this->http = Http::baseUrl('https://api.themoviedb.org/3')
            ->withToken(config('services.tmdb.token'))
            ->withQueryParameters([
                'language' => 'fr-FR',
            ]);
    }

    public function movie(int $tmdbId): array
    {
        return $this->http
            ->get("/movie/{$tmdbId}", ['append_to_response' => 'credits,watch/providers,videos'])
            ->throw()
            ->json();
    }

    public function tvShow(int $tmdbId): array
    {
        return $this->http
            ->get("/tv/{$tmdbId}", ['append_to_response' => 'credits,watch/providers,videos'])
            ->throw()
            ->json();
    }

    public function tvSeason(int $tmdbId, int $seasonNumber): array
    {
        return $this->http
            ->get("/tv/{$tmdbId}/season/{$seasonNumber}")
            ->throw()
            ->json();
    }

    public function person(int $tmdbId): array
    {
        return $this->http
            ->get("/person/{$tmdbId}")
            ->throw()
            ->json();
    }

    public function movieGenres(): array
    {
        return $this->http->get('/genre/movie/list')->throw()->json('genres');
    }

    public function tvGenres(): array
    {
        return $this->http->get('/genre/tv/list')->throw()->json('genres');
    }

    public function watchProviders(): array
    {
        return $this->http
            ->get('/watch/providers/movie', ['watch_region' => 'FR'])
            ->throw()
            ->json('results');
    }

    public function popularMovies(int $page = 1): array
    {
        return $this->http
            ->get('/movie/popular', ['page' => $page])
            ->throw()
            ->json();
    }

    public function nowPlayingMovies(int $page = 1): array
    {
        return $this->http
            ->get('/movie/now_playing', ['page' => $page, 'region' => 'FR'])
            ->throw()
            ->json();
    }

    public function upcomingMovies(int $page = 1): array
    {
        return $this->http
            ->get('/movie/upcoming', ['page' => $page, 'region' => 'FR'])
            ->throw()
            ->json();
    }

    public function popularTvShows(int $page = 1): array
    {
        return $this->http
            ->get('/tv/popular', ['page' => $page])
            ->throw()
            ->json();
    }

    public function searchMovies(string $query, int $page = 1): array
    {
        return $this->http
            ->get('/search/movie', ['query' => $query, 'page' => $page])
            ->throw()
            ->json();
    }

    public function searchTvShows(string $query, int $page = 1): array
    {
        return $this->http
            ->get('/search/tv', ['query' => $query, 'page' => $page])
            ->throw()
            ->json();
    }
}

<?php

namespace App\Services;

use App\Models\Genre;
use App\Models\Movie;
use App\Models\Person;
use App\Models\TvShow;
use App\Models\WatchProvider;
use Illuminate\Support\Str;

class TmdbImporter
{
    public function __construct(private TmdbClient $client) {}

    public function importMovie(int $tmdbId): Movie
    {
        $data = $this->client->movie($tmdbId);

        $movie = Movie::updateOrCreate(
            ['tmdb_id' => $data['id']],
            [
                'imdb_id'          => $data['imdb_id'] ?? null,
                'title'            => $data['title'],
                'original_title'   => $data['original_title'],
                'slug'             => $this->uniqueSlug(Movie::class, $data['title'], $data['id']),
                'overview'         => $data['overview'] ?? null,
                'tagline'          => $data['tagline'] ?? null,
                'poster_path'      => $data['poster_path'] ?? null,
                'backdrop_path'    => $data['backdrop_path'] ?? null,
                'release_date'     => $data['release_date'] ?: null,
                'runtime'          => $data['runtime'] ?? null,
                'original_language'=> $data['original_language'] ?? null,
                'status'           => $data['status'] ?? null,
                'vote_average'     => $data['vote_average'] ?? 0,
                'vote_count'       => $data['vote_count'] ?? 0,
                'popularity'       => $data['popularity'] ?? 0,
                'budget'           => $data['budget'] ?? 0,
                'revenue'          => $data['revenue'] ?? 0,
                'adult'            => $data['adult'] ?? false,
            ]
        );

        $this->syncGenres($movie, $data['genres'] ?? []);
        $this->syncCredits($movie, $data['credits'] ?? []);
        $this->syncMovieWatchProviders($movie, $data['watch/providers']['results']['FR'] ?? []);

        return $movie;
    }

    public function importTvShow(int $tmdbId): TvShow
    {
        $data = $this->client->tvShow($tmdbId);

        $show = TvShow::updateOrCreate(
            ['tmdb_id' => $data['id']],
            [
                'name'               => $data['name'],
                'original_name'      => $data['original_name'],
                'slug'               => $this->uniqueSlug(TvShow::class, $data['name'], $data['id']),
                'overview'           => $data['overview'] ?? null,
                'tagline'            => $data['tagline'] ?? null,
                'poster_path'        => $data['poster_path'] ?? null,
                'backdrop_path'      => $data['backdrop_path'] ?? null,
                'first_air_date'     => $data['first_air_date'] ?: null,
                'last_air_date'      => $data['last_air_date'] ?: null,
                'original_language'  => $data['original_language'] ?? null,
                'status'             => $data['status'] ?? null,
                'type'               => $data['type'] ?? null,
                'number_of_seasons'  => $data['number_of_seasons'] ?? 0,
                'number_of_episodes' => $data['number_of_episodes'] ?? 0,
                'vote_average'       => $data['vote_average'] ?? 0,
                'vote_count'         => $data['vote_count'] ?? 0,
                'popularity'         => $data['popularity'] ?? 0,
            ]
        );

        $this->syncGenres($show, $data['genres'] ?? []);
        $this->syncTvCredits($show, $data['credits'] ?? []);
        $this->syncTvWatchProviders($show, $data['watch/providers']['results']['FR'] ?? []);

        return $show;
    }

    private function syncGenres(Movie|TvShow $model, array $genres): void
    {
        $ids = collect($genres)->map(function ($g) {
            return Genre::firstOrCreate(
                ['tmdb_id' => $g['id']],
                ['name' => $g['name'], 'slug' => Str::slug($g['name'])]
            )->id;
        });

        $model->genres()->sync($ids);
    }

    private function syncCredits(Movie $movie, array $credits): void
    {
        $pivotData = [];

        foreach (($credits['cast'] ?? []) as $member) {
            $person = $this->upsertPerson($member);
            $pivotData[$person->id] = [
                'department' => 'Acting',
                'job'        => 'Actor',
                'character'  => $member['character'] ?? null,
                'order'      => $member['order'] ?? null,
            ];
        }

        foreach (($credits['crew'] ?? []) as $member) {
            $person = $this->upsertPerson($member);
            $pivotData[$person->id] = [
                'department' => $member['department'],
                'job'        => $member['job'] ?? null,
                'character'  => null,
                'order'      => null,
            ];
        }

        $movie->cast()->sync($pivotData);
    }

    private function syncTvCredits(TvShow $show, array $credits): void
    {
        $pivotData = [];

        foreach (($credits['cast'] ?? []) as $member) {
            $person = $this->upsertPerson($member);
            $pivotData[$person->id] = [
                'department' => 'Acting',
                'job'        => 'Actor',
                'character'  => $member['character'] ?? null,
                'order'      => $member['order'] ?? null,
            ];
        }

        foreach (($credits['crew'] ?? []) as $member) {
            $person = $this->upsertPerson($member);
            $pivotData[$person->id] = [
                'department' => $member['department'],
                'job'        => $member['job'] ?? null,
                'character'  => null,
                'order'      => null,
            ];
        }

        $show->cast()->sync($pivotData);
    }

    private function syncMovieWatchProviders(Movie $movie, array $providers): void
    {
        $pivotData = [];

        foreach (['flatrate', 'rent', 'buy', 'free'] as $type) {
            foreach ($providers[$type] ?? [] as $p) {
                $provider = WatchProvider::firstOrCreate(
                    ['tmdb_id' => $p['provider_id']],
                    ['name' => $p['provider_name'], 'logo_path' => $p['logo_path'] ?? null]
                );
                $pivotData[$provider->id] = ['type' => $type];
            }
        }

        $movie->watchProviders()->sync($pivotData);
    }

    private function syncTvWatchProviders(TvShow $show, array $providers): void
    {
        $pivotData = [];

        foreach (['flatrate', 'rent', 'buy', 'free'] as $type) {
            foreach ($providers[$type] ?? [] as $p) {
                $provider = WatchProvider::firstOrCreate(
                    ['tmdb_id' => $p['provider_id']],
                    ['name' => $p['provider_name'], 'logo_path' => $p['logo_path'] ?? null]
                );
                $pivotData[$provider->id] = ['type' => $type];
            }
        }

        $show->watchProviders()->sync($pivotData);
    }

    private function upsertPerson(array $data): Person
    {
        return Person::firstOrCreate(
            ['tmdb_id' => $data['id']],
            [
                'name'         => $data['name'],
                'slug'         => $this->uniqueSlug(Person::class, $data['name'], $data['id']),
                'profile_path' => $data['profile_path'] ?? null,
            ]
        );
    }

    private function uniqueSlug(string $model, string $title, int $tmdbId): string
    {
        $base = Str::slug($title);
        $slug = $base;

        if ($model::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $tmdbId;
        }

        return $slug;
    }
}

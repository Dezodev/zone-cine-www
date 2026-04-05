<?php

namespace App\Services;

use App\Models\Episode;
use App\Models\Genre;
use App\Models\MediaVideo;
use App\Models\Movie;
use App\Models\Person;
use App\Models\Season;
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
        $this->syncVideos($movie, $data['videos']['results'] ?? []);

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
        $this->syncVideos($show, $data['videos']['results'] ?? []);
        $this->syncSeasons($show, $data['seasons'] ?? []);

        return $show;
    }

    private function syncSeasons(TvShow $show, array $seasons): void
    {
        foreach ($seasons as $s) {
            $season = Season::updateOrCreate(
                ['tv_show_id' => $show->id, 'season_number' => $s['season_number']],
                [
                    'tmdb_id'       => $s['id'],
                    'name'          => $s['name'],
                    'overview'      => $s['overview'] ?? null,
                    'poster_path'   => $s['poster_path'] ?? null,
                    'air_date'      => $s['air_date'] ?: null,
                    'episode_count' => $s['episode_count'] ?? 0,
                ]
            );

            $this->syncEpisodes($show, $season);
        }
    }

    private function syncEpisodes(TvShow $show, Season $season): void
    {
        try {
            $data = $this->client->tvSeason($show->tmdb_id, $season->season_number);
        } catch (\Throwable) {
            return;
        }

        foreach ($data['episodes'] ?? [] as $e) {
            Episode::updateOrCreate(
                ['season_id' => $season->id, 'episode_number' => $e['episode_number']],
                [
                    'tmdb_id'        => $e['id'],
                    'name'           => $e['name'],
                    'overview'       => $e['overview'] ?? null,
                    'still_path'     => $e['still_path'] ?? null,
                    'air_date'       => $e['air_date'] ?: null,
                    'runtime'        => $e['runtime'] ?? null,
                    'vote_average'   => $e['vote_average'] ?? 0,
                ]
            );
        }
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

    private function syncVideos(Movie|TvShow $model, array $videos): void
    {
        foreach ($videos as $video) {
            if (($video['site'] ?? '') !== 'YouTube') {
                continue;
            }

            MediaVideo::updateOrCreate(
                [
                    'mediable_type' => $model::class,
                    'mediable_id'   => $model->id,
                    'youtube_key'   => $video['key'],
                ],
                [
                    'name'         => $video['name'],
                    'type'         => $video['type'],
                    'site'         => $video['site'],
                    'official'     => $video['official'] ?? false,
                    'published_at' => $video['published_at'] ?? null,
                ]
            );
        }
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
        return Str::slug($title) . '-' . $tmdbId;
    }
}

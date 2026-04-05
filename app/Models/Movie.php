<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property int                                                               $id
 * @property int                                                               $tmdb_id           Identifiant TMDB du film
 * @property string|null                                                       $imdb_id           Identifiant IMDb (ex: tt0468569)
 * @property string                                                            $title             Titre en français
 * @property string                                                            $original_title    Titre original
 * @property string                                                            $slug              Slug URL
 * @property string|null                                                       $overview          Synopsis
 * @property string|null                                                       $tagline           Accroche du film
 * @property string|null                                                       $poster_path       Chemin de l'affiche (TMDB)
 * @property string|null                                                       $backdrop_path     Chemin de l'image de fond (TMDB)
 * @property \Illuminate\Support\Carbon|null                                   $release_date      Date de sortie
 * @property int|null                                                          $runtime           Durée en minutes
 * @property string|null                                                       $original_language Code langue ISO 639-1 (ex: en, fr)
 * @property string|null                                                       $status            Statut (Released, In Production…)
 * @property float                                                             $vote_average      Note moyenne TMDB (0-10)
 * @property int                                                               $vote_count        Nombre de votes TMDB
 * @property float                                                             $popularity        Score de popularité TMDB
 * @property int                                                               $budget            Budget en dollars
 * @property int                                                               $revenue           Recettes mondiales en dollars
 * @property bool                                                              $adult             Film pour adultes
 * @property bool                                                              $hidden            Caché de toutes les listes
 * @property \Illuminate\Support\Carbon                                        $created_at
 * @property \Illuminate\Support\Carbon                                        $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Genre>         $genres         Genres du film
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Person>        $cast           Acteurs (pivot character, order)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Person>        $crew           Équipe technique (pivot department, job)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Person>        $directors      Réalisateurs uniquement
 * @property-read \Illuminate\Database\Eloquent\Collection<int, WatchProvider> $watchProviders Plateformes de streaming FR (pivot type)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, MediaVideo>   $videos         Bandes-annonces et vidéos YouTube
 * @property-read MediaVideo|null                                              $trailer        Bande-annonce officielle principale
 */
class Movie extends Model
{
    protected $fillable = [
        'tmdb_id', 'imdb_id', 'title', 'original_title', 'slug',
        'overview', 'tagline', 'poster_path', 'backdrop_path',
        'release_date', 'runtime', 'original_language', 'status',
        'vote_average', 'vote_count', 'popularity',
        'budget', 'revenue', 'adult', 'hidden',
    ];

    protected $casts = [
        'release_date' => 'date',
        'adult'        => 'boolean',
        'hidden'       => 'boolean',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('visible', fn (Builder $q) => $q->where('hidden', false));
    }

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class);
    }

    public function cast(): BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'movie_person')
            ->wherePivot('department', 'Acting')
            ->withPivot('character', 'order')
            ->orderByPivot('order');
    }

    public function crew(): BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'movie_person')
            ->wherePivot('department', '!=', 'Acting')
            ->withPivot('department', 'job');
    }

    public function directors(): BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'movie_person')
            ->wherePivot('job', 'Director')
            ->withPivot('job');
    }

    public function watchProviders(): BelongsToMany
    {
        return $this->belongsToMany(WatchProvider::class, 'movie_watch_provider')
            ->withPivot('type');
    }

    public function videos(): MorphMany
    {
        return $this->morphMany(MediaVideo::class, 'mediable')
            ->orderByDesc('official')
            ->orderBy('published_at');
    }

    public function getTrailerAttribute(): ?MediaVideo
    {
        return $this->videos
            ->where('type', 'Trailer')
            ->sortByDesc('official')
            ->first();
    }
}

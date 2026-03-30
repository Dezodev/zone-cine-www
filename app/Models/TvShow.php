<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int                                                               $id
 * @property int                                                               $tmdb_id             Identifiant TMDB de la série
 * @property string                                                            $name                Titre en français
 * @property string                                                            $original_name       Titre original
 * @property string                                                            $slug                Slug URL
 * @property string|null                                                       $overview            Synopsis
 * @property string|null                                                       $tagline             Accroche de la série
 * @property string|null                                                       $poster_path         Chemin de l'affiche (TMDB)
 * @property string|null                                                       $backdrop_path       Chemin de l'image de fond (TMDB)
 * @property \Illuminate\Support\Carbon|null                                   $first_air_date      Date de première diffusion
 * @property \Illuminate\Support\Carbon|null                                   $last_air_date       Date du dernier épisode diffusé
 * @property string|null                                                       $original_language   Code langue ISO 639-1 (ex: en, fr)
 * @property string|null                                                       $status              Statut (Returning Series, Ended…)
 * @property string|null                                                       $type                Type (Scripted, Reality, Documentary…)
 * @property int                                                               $number_of_seasons   Nombre de saisons
 * @property int                                                               $number_of_episodes  Nombre total d'épisodes
 * @property float                                                             $vote_average        Note moyenne TMDB (0-10)
 * @property int                                                               $vote_count          Nombre de votes TMDB
 * @property float                                                             $popularity          Score de popularité TMDB
 * @property \Illuminate\Support\Carbon                                        $created_at
 * @property \Illuminate\Support\Carbon                                        $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Genre>         $genres         Genres de la série
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Person>        $cast           Acteurs (pivot character, order)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Person>        $crew           Équipe technique (pivot department, job)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, WatchProvider> $watchProviders Plateformes de streaming FR (pivot type)
 */
class TvShow extends Model
{
    protected $fillable = [
        'tmdb_id', 'name', 'original_name', 'slug',
        'overview', 'tagline', 'poster_path', 'backdrop_path',
        'first_air_date', 'last_air_date', 'original_language',
        'status', 'type', 'number_of_seasons', 'number_of_episodes',
        'vote_average', 'vote_count', 'popularity',
    ];

    protected $casts = [
        'first_air_date' => 'date',
        'last_air_date'  => 'date',
    ];

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class);
    }

    public function cast(): BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'person_tv_show')
            ->wherePivot('department', 'Acting')
            ->withPivot('character', 'order')
            ->orderByPivot('order');
    }

    public function crew(): BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'person_tv_show')
            ->wherePivotNot('department', 'Acting')
            ->withPivot('department', 'job');
    }

    public function watchProviders(): BelongsToMany
    {
        return $this->belongsToMany(WatchProvider::class, 'tv_show_watch_provider')
            ->withPivot('type');
    }
}

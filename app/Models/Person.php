<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int                                                          $id
 * @property int                                                          $tmdb_id         Identifiant TMDB de la personne
 * @property string                                                       $name            Nom complet
 * @property string                                                       $slug            Slug URL
 * @property string|null                                                  $profile_path    Chemin de la photo de profil (TMDB)
 * @property \Illuminate\Support\Carbon|null                              $birthday        Date de naissance
 * @property \Illuminate\Support\Carbon|null                              $deathday        Date de décès
 * @property string|null                                                  $place_of_birth  Lieu de naissance
 * @property string|null                                                  $biography       Biographie
 * @property \Illuminate\Support\Carbon                                   $created_at
 * @property \Illuminate\Support\Carbon                                   $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Movie>   $movies    Films avec pivot department/job/character/order
 * @property-read \Illuminate\Database\Eloquent\Collection<int, TvShow>  $tvShows   Séries avec pivot department/job/character/order
 */
class Person extends Model
{
    protected $fillable = [
        'tmdb_id', 'name', 'slug', 'profile_path',
        'birthday', 'deathday', 'place_of_birth', 'biography',
    ];

    protected $casts = [
        'birthday'  => 'date',
        'deathday'  => 'date',
    ];

    public function movies(): BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'movie_person')
            ->withPivot('department', 'job', 'character', 'order');
    }

    public function tvShows(): BelongsToMany
    {
        return $this->belongsToMany(TvShow::class, 'person_tv_show')
            ->withPivot('department', 'job', 'character', 'order');
    }
}

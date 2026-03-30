<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int                                                          $id
 * @property int                                                          $tmdb_id    Identifiant TMDB du fournisseur
 * @property string                                                       $name       Nom de la plateforme (ex: Netflix, Canal+)
 * @property string|null                                                  $logo_path  Chemin du logo (TMDB)
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Movie>   $movies    Films disponibles, pivot type (flatrate/rent/buy/free)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, TvShow>  $tvShows   Séries disponibles, pivot type (flatrate/rent/buy/free)
 */
class WatchProvider extends Model
{
    public $timestamps = false;

    protected $fillable = ['tmdb_id', 'name', 'logo_path'];

    public function movies(): BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'movie_watch_provider')
            ->withPivot('type');
    }

    public function tvShows(): BelongsToMany
    {
        return $this->belongsToMany(TvShow::class, 'tv_show_watch_provider')
            ->withPivot('type');
    }
}

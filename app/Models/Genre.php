<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int                                                          $id
 * @property int                                                          $tmdb_id    Identifiant TMDB du genre
 * @property string                                                       $name       Nom du genre (ex: Action, Comédie)
 * @property string                                                       $slug       Slug URL du genre
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Movie>   $movies     Films de ce genre
 * @property-read \Illuminate\Database\Eloquent\Collection<int, TvShow>  $tvShows    Séries de ce genre
 */
class Genre extends Model
{
    public $timestamps = false;

    protected $fillable = ['tmdb_id', 'name', 'slug'];

    public function movies(): BelongsToMany
    {
        return $this->belongsToMany(Movie::class);
    }

    public function tvShows(): BelongsToMany
    {
        return $this->belongsToMany(TvShow::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

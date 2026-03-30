<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

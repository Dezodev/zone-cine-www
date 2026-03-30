<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

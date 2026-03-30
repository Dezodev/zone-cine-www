<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Movie extends Model
{
    protected $fillable = [
        'tmdb_id', 'imdb_id', 'title', 'original_title', 'slug',
        'overview', 'tagline', 'poster_path', 'backdrop_path',
        'release_date', 'runtime', 'original_language', 'status',
        'vote_average', 'vote_count', 'popularity',
        'budget', 'revenue', 'adult',
    ];

    protected $casts = [
        'release_date' => 'date',
        'adult'        => 'boolean',
    ];

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
            ->wherePivotNot('department', 'Acting')
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
}

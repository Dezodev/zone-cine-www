<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

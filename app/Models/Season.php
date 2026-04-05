<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int                                                            $id
 * @property int                                                            $tv_show_id
 * @property int                                                            $tmdb_id
 * @property int                                                            $season_number
 * @property string                                                         $name
 * @property string|null                                                    $overview
 * @property string|null                                                    $poster_path
 * @property \Illuminate\Support\Carbon|null                                $air_date
 * @property int                                                            $episode_count
 *
 * @property-read TvShow                                                    $tvShow
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Episode>   $episodes
 */
class Season extends Model
{
    protected $fillable = [
        'tv_show_id', 'tmdb_id', 'season_number',
        'name', 'overview', 'poster_path', 'air_date', 'episode_count',
    ];

    protected $casts = [
        'air_date' => 'date',
    ];

    public function tvShow(): BelongsTo
    {
        return $this->belongsTo(TvShow::class);
    }

    public function episodes(): HasMany
    {
        return $this->hasMany(Episode::class)->orderBy('episode_number');
    }

    public function posterUrl(string $size = 'w300'): ?string
    {
        return $this->poster_path
            ? "https://image.tmdb.org/t/p/{$size}{$this->poster_path}"
            : null;
    }
}

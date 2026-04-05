<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int                             $id
 * @property int                             $season_id
 * @property int                             $tmdb_id
 * @property int                             $episode_number
 * @property string                          $name
 * @property string|null                     $overview
 * @property string|null                     $still_path
 * @property \Illuminate\Support\Carbon|null $air_date
 * @property int|null                        $runtime
 * @property float                           $vote_average
 *
 * @property-read Season                     $season
 */
class Episode extends Model
{
    protected $fillable = [
        'season_id', 'tmdb_id', 'episode_number',
        'name', 'overview', 'still_path', 'air_date', 'runtime', 'vote_average',
    ];

    protected $casts = [
        'air_date' => 'date',
    ];

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class);
    }

    public function stillUrl(string $size = 'w300'): ?string
    {
        return $this->still_path
            ? "https://image.tmdb.org/t/p/{$size}{$this->still_path}"
            : null;
    }
}

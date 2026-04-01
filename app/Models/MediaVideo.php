<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int                         $id
 * @property string                      $mediable_type    Classe du modèle parent (Movie, TvShow)
 * @property int                         $mediable_id      ID du modèle parent
 * @property string                      $youtube_key      Identifiant de la vidéo YouTube
 * @property string                      $name             Titre de la vidéo
 * @property string                      $type             Type (Trailer, Teaser, Clip, Featurette…)
 * @property string                      $site             Plateforme (YouTube, Vimeo)
 * @property bool                        $official         Vidéo officielle
 * @property \Illuminate\Support\Carbon|null $published_at Date de publication
 * @property \Illuminate\Support\Carbon  $created_at
 * @property \Illuminate\Support\Carbon  $updated_at
 *
 * @property-read Movie|TvShow           $mediable         Film ou série associé
 */
class MediaVideo extends Model
{
    protected $fillable = [
        'mediable_type', 'mediable_id',
        'youtube_key', 'name', 'type', 'site', 'official', 'published_at',
    ];

    protected $casts = [
        'official'     => 'boolean',
        'published_at' => 'datetime',
    ];

    public function mediable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getYoutubeUrlAttribute(): string
    {
        return "https://www.youtube.com/watch?v={$this->youtube_key}";
    }

    public function getYoutubeEmbedUrlAttribute(): string
    {
        return "https://www.youtube.com/embed/{$this->youtube_key}";
    }
}

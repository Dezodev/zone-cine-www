<?php

use App\Models\Movie;
use App\Models\TvShow;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Movie::query()->select('id', 'title', 'tmdb_id')->each(function (Movie $movie) {
            $movie->updateQuietly(['slug' => Str::slug($movie->title) . '-' . $movie->tmdb_id]);
        });

        TvShow::query()->select('id', 'name', 'tmdb_id')->each(function (TvShow $show) {
            $show->updateQuietly(['slug' => Str::slug($show->name) . '-' . $show->tmdb_id]);
        });
    }

    public function down(): void
    {
        // Régénération inverse non implémentée — réimporter depuis TMDB si nécessaire
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->decimal('weighted_score', 8, 4)->default(0)->index()->after('popularity');
        });

        Schema::table('tv_shows', function (Blueprint $table) {
            $table->decimal('weighted_score', 8, 4)->default(0)->index()->after('popularity');
        });

        // Bayesian score × recency boost (up to +30% for content < 10 years old)
        DB::statement("
            UPDATE movies
            SET weighted_score =
                (vote_average * vote_count) / (vote_count + 1000)
                * (1 + GREATEST(0, (10 - TIMESTAMPDIFF(YEAR, release_date, CURDATE())) / 10) * 0.3)
            WHERE release_date IS NOT NULL
        ");
        DB::statement("
            UPDATE movies
            SET weighted_score = (vote_average * vote_count) / (vote_count + 1000)
            WHERE release_date IS NULL
        ");

        DB::statement("
            UPDATE tv_shows
            SET weighted_score =
                (vote_average * vote_count) / (vote_count + 1000)
                * (1 + GREATEST(0, (10 - TIMESTAMPDIFF(YEAR, first_air_date, CURDATE())) / 10) * 0.3)
            WHERE first_air_date IS NOT NULL
        ");
        DB::statement("
            UPDATE tv_shows
            SET weighted_score = (vote_average * vote_count) / (vote_count + 1000)
            WHERE first_air_date IS NULL
        ");
    }

    public function down(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->dropColumn('weighted_score');
        });

        Schema::table('tv_shows', function (Blueprint $table) {
            $table->dropColumn('weighted_score');
        });
    }
};

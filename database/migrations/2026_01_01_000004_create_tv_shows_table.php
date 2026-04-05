<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tv_shows', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('tmdb_id')->unique();
            $table->string('name');
            $table->string('original_name');
            $table->string('slug')->unique();
            $table->text('overview')->nullable();
            $table->string('tagline')->nullable();
            $table->string('poster_path')->nullable();
            $table->string('backdrop_path')->nullable();
            $table->date('first_air_date')->nullable()->index();
            $table->date('last_air_date')->nullable();
            $table->string('original_language', 10)->nullable();
            $table->string('status')->nullable();          // Returning Series, Ended…
            $table->string('type')->nullable();            // Scripted, Reality…
            $table->unsignedSmallInteger('number_of_seasons')->default(0);
            $table->unsignedSmallInteger('number_of_episodes')->default(0);
            $table->decimal('vote_average', 4, 2)->default(0);
            $table->unsignedInteger('vote_count')->default(0);
            $table->decimal('popularity', 10, 4)->default(0)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tv_shows');
    }
};

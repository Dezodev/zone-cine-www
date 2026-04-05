<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Films <-> Genres
        Schema::create('genre_movie', function (Blueprint $table) {
            $table->foreignId('movie_id')->constrained()->cascadeOnDelete();
            $table->foreignId('genre_id')->constrained()->cascadeOnDelete();
            $table->primary(['movie_id', 'genre_id']);
        });

        // Séries <-> Genres
        Schema::create('genre_tv_show', function (Blueprint $table) {
            $table->foreignId('tv_show_id')->constrained()->cascadeOnDelete();
            $table->foreignId('genre_id')->constrained()->cascadeOnDelete();
            $table->primary(['tv_show_id', 'genre_id']);
        });

        // Films <-> Personnes (casting + équipe)
        Schema::create('movie_person', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id')->constrained()->cascadeOnDelete();
            $table->foreignId('person_id')->constrained('people')->cascadeOnDelete();
            $table->string('department');   // Acting, Directing, Writing…
            $table->string('job')->nullable();
            $table->string('character')->nullable();
            $table->unsignedSmallInteger('order')->nullable();
            $table->index(['movie_id', 'department']);
        });

        // Séries <-> Personnes
        Schema::create('person_tv_show', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tv_show_id')->constrained()->cascadeOnDelete();
            $table->foreignId('person_id')->constrained('people')->cascadeOnDelete();
            $table->string('department');
            $table->string('job')->nullable();
            $table->string('character')->nullable();
            $table->unsignedSmallInteger('order')->nullable();
            $table->index(['tv_show_id', 'department']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('person_tv_show');
        Schema::dropIfExists('movie_person');
        Schema::dropIfExists('genre_tv_show');
        Schema::dropIfExists('genre_movie');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('watch_providers', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('tmdb_id')->unique();
            $table->string('name');
            $table->string('logo_path')->nullable();
        });

        // Films <-> Plateformes (filtré France)
        Schema::create('movie_watch_provider', function (Blueprint $table) {
            $table->foreignId('movie_id')->constrained()->cascadeOnDelete();
            $table->foreignId('watch_provider_id')->constrained()->cascadeOnDelete();
            $table->string('type');   // flatrate, rent, buy, free
            $table->primary(['movie_id', 'watch_provider_id', 'type'], 'movie_provider_type_primary');
        });

        // Séries <-> Plateformes
        Schema::create('tv_show_watch_provider', function (Blueprint $table) {
            $table->foreignId('tv_show_id')->constrained()->cascadeOnDelete();
            $table->foreignId('watch_provider_id')->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->primary(['tv_show_id', 'watch_provider_id', 'type'], 'tvshow_provider_type_primary');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tv_show_watch_provider');
        Schema::dropIfExists('movie_watch_provider');
        Schema::dropIfExists('watch_providers');
    }
};

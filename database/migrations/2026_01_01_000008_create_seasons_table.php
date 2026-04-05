<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seasons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tv_show_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('tmdb_id');
            $table->unsignedSmallInteger('season_number');
            $table->string('name');
            $table->text('overview')->nullable();
            $table->string('poster_path')->nullable();
            $table->date('air_date')->nullable();
            $table->unsignedSmallInteger('episode_count')->default(0);
            $table->timestamps();

            $table->unique(['tv_show_id', 'season_number']);
            $table->index('tv_show_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seasons');
    }
};

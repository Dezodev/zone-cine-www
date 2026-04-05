<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('tmdb_id')->unique();
            $table->string('imdb_id')->nullable()->index();
            $table->string('title');
            $table->string('original_title');
            $table->string('slug')->unique();
            $table->text('overview')->nullable();
            $table->string('tagline')->nullable();
            $table->string('poster_path')->nullable();
            $table->string('backdrop_path')->nullable();
            $table->date('release_date')->nullable()->index();
            $table->unsignedSmallInteger('runtime')->nullable()->comment('en minutes');
            $table->string('original_language', 10)->nullable();
            $table->string('status')->nullable();          // Released, In Production…
            $table->decimal('vote_average', 4, 2)->default(0);
            $table->unsignedInteger('vote_count')->default(0);
            $table->decimal('popularity', 10, 4)->default(0)->index();
            $table->unsignedBigInteger('budget')->default(0);
            $table->unsignedBigInteger('revenue')->default(0);
            $table->boolean('adult')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};

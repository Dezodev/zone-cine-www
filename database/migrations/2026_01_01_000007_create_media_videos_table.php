<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_videos', function (Blueprint $table) {
            $table->id();
            $table->morphs('mediable');
            $table->string('youtube_key', 20);
            $table->string('name');
            $table->string('type', 50);        // Trailer, Teaser, Clip, Featurette…
            $table->string('site', 20)->default('YouTube');
            $table->boolean('official')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->unique(['mediable_type', 'mediable_id', 'youtube_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_videos');
    }
};

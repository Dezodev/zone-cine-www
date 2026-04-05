<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->boolean('hidden')->default(false)->after('adult');
        });

        Schema::table('tv_shows', function (Blueprint $table) {
            $table->boolean('hidden')->default(false)->after('popularity');
        });
    }

    public function down(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->dropColumn('hidden');
        });

        Schema::table('tv_shows', function (Blueprint $table) {
            $table->dropColumn('hidden');
        });
    }
};

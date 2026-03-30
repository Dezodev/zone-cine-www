<?php

namespace App\Jobs;

use App\Services\TmdbImporter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ImportTmdbTvShow implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 10;

    public function __construct(public readonly int $tmdbId) {}

    public function handle(TmdbImporter $importer): void
    {
        $importer->importTvShow($this->tmdbId);
    }

    public function failed(\Throwable $e): void
    {
        Log::error("ImportTmdbTvShow failed for ID {$this->tmdbId}: {$e->getMessage()}");
    }
}

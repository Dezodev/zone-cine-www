<?php

namespace App\Console\Commands;

use App\Jobs\ImportTmdbTvShow;
use App\Models\TvShow;
use Illuminate\Console\Command;

class SyncTvShowSeasons extends Command
{
    protected $signature = 'tmdb:sync-seasons
        {--id=  : Synchroniser une seule série par son tmdb_id}';

    protected $description = 'Synchronise les saisons et épisodes des séries depuis TMDB';

    public function handle(): int
    {
        $query = TvShow::query()->select('id', 'tmdb_id', 'name');

        if ($id = $this->option('id')) {
            $query->where('tmdb_id', $id);
        } else {
            // Seulement les séries sans saisons importées
            $query->whereDoesntHave('seasons');
        }

        $shows = $query->get();

        if ($shows->isEmpty()) {
            $this->info('Aucune série à synchroniser.');
            return self::SUCCESS;
        }

        $this->info("Dispatch de {$shows->count()} série(s) en queue…");

        $bar = $this->output->createProgressBar($shows->count());
        $bar->start();

        foreach ($shows as $show) {
            ImportTmdbTvShow::dispatch($show->tmdb_id);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Jobs dispatchés sur la queue tmdb-import.');

        return self::SUCCESS;
    }
}

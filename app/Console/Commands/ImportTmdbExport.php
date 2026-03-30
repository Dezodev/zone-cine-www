<?php

namespace App\Console\Commands;

use App\Jobs\ImportTmdbMovie;
use App\Jobs\ImportTmdbTvShow;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ImportTmdbExport extends Command
{
    protected $signature = 'tmdb:import-export
        {--type=movies   : Type à importer : movies ou tv}
        {--min-popularity=10 : Score de popularité TMDB minimum (défaut : 10)}
        {--date=         : Date de l\'export au format MM_DD_YYYY (défaut : hier)}
        {--dry-run       : Affiche le nombre d\'entrées sans dispatcher les jobs}';

    protected $description = 'Importe le catalogue TMDB depuis les exports journaliers (movie_ids / tv_series_ids)';

    private const EXPORT_BASE_URL = 'http://files.tmdb.org/p/exports';

    private const TYPES = [
        'movies' => 'movie_ids',
        'tv'     => 'tv_series_ids',
    ];

    public function handle(): int
    {
        $type = $this->option('type');
        $minPopularity = (float) $this->option('min-popularity');
        $dryRun = $this->option('dry-run');

        if (! array_key_exists($type, self::TYPES)) {
            $this->error("Type invalide : \"{$type}\". Valeurs acceptées : movies, tv");
            return self::FAILURE;
        }

        $date = $this->resolveDate($this->option('date'));
        $url  = $this->buildUrl($type, $date);

        $this->info("Export TMDB : {$url}");
        $this->info("Popularité minimum : {$minPopularity}");
        $dryRun && $this->warn('Mode dry-run activé — aucun job ne sera dispatché.');
        $this->newLine();

        // --- Téléchargement ---
        $tempPath = $this->download($url);
        if ($tempPath === null) {
            return self::FAILURE;
        }

        // --- Lecture en streaming + dispatch ---
        try {
            [$dispatched, $skipped] = $this->process($tempPath, $type, $minPopularity, $dryRun);
        } finally {
            @unlink($tempPath);
        }

        $this->newLine();
        $this->table(
            ['Jobs dispatchés', 'Entrées ignorées (popularité)'],
            [[$dispatched, $skipped]]
        );

        $label = $dryRun ? 'seraient importés' : 'jobs dispatchés dans la queue';
        $this->info("{$dispatched} titres {$label}.");

        Log::info("tmdb:import-export [{$type}] date={$date} min-popularity={$minPopularity} dispatched={$dispatched} skipped={$skipped} dry-run={$dryRun}");

        return self::SUCCESS;
    }

    // -------------------------------------------------------------------------

    private function resolveDate(?string $date): string
    {
        if ($date) {
            return $date;
        }

        // Les exports sont générés dans la nuit ; on prend la veille par défaut.
        return now()->subDay()->format('m_d_Y');
    }

    private function buildUrl(string $type, string $date): string
    {
        $prefix = self::TYPES[$type];

        return sprintf('%s/%s_%s.json.gz', self::EXPORT_BASE_URL, $prefix, $date);
    }

    private function download(string $url): ?string
    {
        $this->line("Téléchargement en cours...");

        $tempPath = tempnam(sys_get_temp_dir(), 'tmdb_export_');

        try {
            $response = Http::timeout(120)->sink($tempPath)->get($url);
        } catch (\Throwable $e) {
            $this->error("Erreur réseau : {$e->getMessage()}");
            @unlink($tempPath);
            return null;
        }

        if (! $response->successful()) {
            $this->error("Téléchargement échoué (HTTP {$response->status()}) : {$url}");
            @unlink($tempPath);
            return null;
        }

        $sizeKb = round(filesize($tempPath) / 1024);
        $this->info("Fichier téléchargé ({$sizeKb} Ko).");

        return $tempPath;
    }

    /**
     * Lit le fichier .json.gz ligne par ligne et dispatche les jobs.
     *
     * @return array{int, int} [$dispatched, $skipped]
     */
    private function process(string $tempPath, string $type, float $minPopularity, bool $dryRun): array
    {
        $gz = @gzopen($tempPath, 'rb');

        if ($gz === false) {
            $this->error('Impossible d\'ouvrir le fichier gzip.');
            return [0, 0];
        }

        $dispatched = 0;
        $skipped    = 0;
        $bar        = null;

        $this->line('Traitement des entrées...');

        while (! gzeof($gz)) {
            $line = trim((string) gzgets($gz, 4096));

            if ($line === '') {
                continue;
            }

            $entry = json_decode($line, true);

            if (! isset($entry['id'], $entry['popularity'])) {
                continue;
            }

            if ((float) $entry['popularity'] < $minPopularity) {
                $skipped++;
                continue;
            }

            if (! $dryRun) {
                $job = $type === 'movies'
                    ? new ImportTmdbMovie((int) $entry['id'])
                    : new ImportTmdbTvShow((int) $entry['id']);

                dispatch($job)->onQueue('tmdb-import');
            }

            $dispatched++;

            // Feedback visuel tous les 1 000 items
            if ($dispatched % 1000 === 0) {
                $this->line("  → {$dispatched} jobs dispatchés...");
            }
        }

        gzclose($gz);

        return [$dispatched, $skipped];
    }
}

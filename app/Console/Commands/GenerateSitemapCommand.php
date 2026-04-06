<?php

namespace App\Console\Commands;

use App\Jobs\GenerateSitemap;
use Illuminate\Console\Command;

class GenerateSitemapCommand extends Command
{
    protected $signature = 'sitemap:generate';

    protected $description = 'Génère les sitemaps XML du site dans le dossier public';

    public function handle(): int
    {
        $this->info('Génération des sitemaps…');

        (new GenerateSitemap())->handle();

        $this->info('Sitemaps générés avec succès :');
        $this->line('  public/sitemap.xml (index)');
        $this->line('  public/sitemap-static.xml');
        $this->line('  public/sitemap-movies.xml');
        $this->line('  public/sitemap-tv.xml');
        $this->line('  public/sitemap-people.xml');
        $this->line('  public/sitemap-genres.xml');

        return Command::SUCCESS;
    }
}

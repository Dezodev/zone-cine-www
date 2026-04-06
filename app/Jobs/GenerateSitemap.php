<?php

namespace App\Jobs;

use App\Models\Genre;
use App\Models\Movie;
use App\Models\Person;
use App\Models\TvShow;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapIndex;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap implements ShouldQueue
{
    use Queueable;

    public function handle(): void
    {
        $this->generateStaticSitemap();
        $this->generateMoviesSitemap();
        $this->generateTvSitemap();
        $this->generatePeopleSitemap();
        $this->generateGenresSitemap();
        $this->generateSitemapIndex();
    }

    private function generateStaticSitemap(): void
    {
        Sitemap::create()
            ->add(Url::create(route('home'))->setPriority(1.0)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY))
            ->add(Url::create(route('movies.index'))->setPriority(0.9)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY))
            ->add(Url::create(route('tv.index'))->setPriority(0.9)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY))
            ->add(Url::create(route('search'))->setPriority(0.5)->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY))
            ->add(Url::create(route('legal.mentions'))->setPriority(0.1)->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY))
            ->add(Url::create(route('legal.privacy'))->setPriority(0.1)->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY))
            ->writeToFile(public_path('sitemap-static.xml'));
    }

    private function generateMoviesSitemap(): void
    {
        $sitemap = Sitemap::create();

        Movie::query()
            ->select(['id', 'slug', 'updated_at'])
            ->orderByDesc('popularity')
            ->chunk(500, function ($movies) use ($sitemap) {
                foreach ($movies as $movie) {
                    $sitemap->add(
                        Url::create(route('movies.show', $movie->slug))
                            ->setLastModificationDate($movie->updated_at)
                            ->setPriority(0.8)
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    );
                }
            });

        $sitemap->writeToFile(public_path('sitemap-movies.xml'));
    }

    private function generateTvSitemap(): void
    {
        $sitemap = Sitemap::create();

        TvShow::query()
            ->select(['id', 'slug', 'updated_at'])
            ->orderByDesc('popularity')
            ->chunk(500, function ($shows) use ($sitemap) {
                foreach ($shows as $show) {
                    $sitemap->add(
                        Url::create(route('tv.show', $show->slug))
                            ->setLastModificationDate($show->updated_at)
                            ->setPriority(0.8)
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    );
                }
            });

        $sitemap->writeToFile(public_path('sitemap-tv.xml'));
    }

    private function generatePeopleSitemap(): void
    {
        $sitemap = Sitemap::create();

        Person::query()
            ->select(['id', 'slug', 'updated_at'])
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->chunk(500, function ($people) use ($sitemap) {
                foreach ($people as $person) {
                    $sitemap->add(
                        Url::create(route('people.show', $person->slug))
                            ->setLastModificationDate($person->updated_at)
                            ->setPriority(0.6)
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                    );
                }
            });

        $sitemap->writeToFile(public_path('sitemap-people.xml'));
    }

    private function generateGenresSitemap(): void
    {
        $sitemap = Sitemap::create();

        Genre::query()
            ->select(['id', 'slug'])
            ->whereHas('movies')
            ->each(function ($genre) use ($sitemap) {
                $sitemap->add(
                    Url::create(route('genres.movies', $genre->slug))
                        ->setPriority(0.7)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                );
            });

        Genre::query()
            ->select(['id', 'slug'])
            ->whereHas('tvShows')
            ->each(function ($genre) use ($sitemap) {
                $sitemap->add(
                    Url::create(route('genres.tv', $genre->slug))
                        ->setPriority(0.7)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                );
            });

        $sitemap->writeToFile(public_path('sitemap-genres.xml'));
    }

    private function generateSitemapIndex(): void
    {
        SitemapIndex::create()
            ->add(url('sitemap-static.xml'))
            ->add(url('sitemap-movies.xml'))
            ->add(url('sitemap-tv.xml'))
            ->add(url('sitemap-people.xml'))
            ->add(url('sitemap-genres.xml'))
            ->writeToFile(public_path('sitemap.xml'));
    }
}

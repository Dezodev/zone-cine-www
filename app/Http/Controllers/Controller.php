<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Person;
use App\Models\TvShow;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\TwitterCard;

abstract class Controller
{
    protected function setSeo(
        string $title,
        string $description,
        ?string $image = null,
        string $ogType = 'website',
    ): void {
        $fullTitle = str_ends_with($title, 'Zone Ciné') ? $title : $title.' — Zone Ciné';

        SEOMeta::setTitle($title);
        SEOMeta::setTitleSeparator(' — ');
        SEOMeta::addMeta('robots', 'index, follow');

        $desc = mb_strimwidth($description, 0, 160, '…');
        SEOMeta::setDescription($desc);
        SEOMeta::setCanonical(url()->current());

        OpenGraph::setTitle($fullTitle);
        OpenGraph::setDescription($desc);
        OpenGraph::setType($ogType);
        OpenGraph::setUrl(url()->current());
        OpenGraph::setSiteName('Zone Ciné');

        if ($image) {
            OpenGraph::addImage($image, ['width' => 1280, 'height' => 720]);
        } else {
            OpenGraph::addImage(asset('images/og-default.jpg'));
        }

        TwitterCard::setType('summary_large_image');
        TwitterCard::setTitle($fullTitle);
        TwitterCard::setDescription($desc);

        if ($image) {
            TwitterCard::setImage($image);
        }
    }

    protected function setMovieSeo(Movie $movie): void
    {
        $description = $movie->overview
            ?: "Découvrez {$movie->title} sur Zone Ciné : streaming, bande-annonce et infos complètes.";

        $image = $movie->backdrop_path
            ? 'https://image.tmdb.org/t/p/w1280'.$movie->backdrop_path
            : null;

        $this->setSeo($movie->title, $description, $image, 'video.movie');

        JsonLd::setType('Movie');
        JsonLd::setTitle($movie->title);
        JsonLd::setDescription(mb_strimwidth($description, 0, 300, '…'));
        JsonLd::setUrl('current');

        if ($movie->poster_path) {
            JsonLd::setImage('https://image.tmdb.org/t/p/w500'.$movie->poster_path);
        }

        if ($movie->release_date) {
            JsonLd::addValue('datePublished', $movie->release_date->format('Y-m-d'));
        }

        if ($movie->vote_average > 0) {
            JsonLd::addValue('aggregateRating', [
                '@type'       => 'AggregateRating',
                'ratingValue' => round($movie->vote_average, 1),
                'bestRating'  => 10,
                'worstRating' => 0,
                'ratingCount' => $movie->vote_count,
            ]);
        }

        if ($movie->relationLoaded('directors') && $movie->directors->isNotEmpty()) {
            JsonLd::addValue('director', $movie->directors->map(fn ($d) => [
                '@type' => 'Person',
                'name'  => $d->name,
            ])->toArray());
        }
    }

    protected function setTvShowSeo(TvShow $show): void
    {
        $description = $show->overview
            ?: "Découvrez {$show->name} sur Zone Ciné : streaming, bande-annonce et infos complètes.";

        $image = $show->backdrop_path
            ? 'https://image.tmdb.org/t/p/w1280'.$show->backdrop_path
            : null;

        $this->setSeo($show->name, $description, $image, 'video.tv_show');

        JsonLd::setType('TVSeries');
        JsonLd::setTitle($show->name);
        JsonLd::setDescription(mb_strimwidth($description, 0, 300, '…'));
        JsonLd::setUrl('current');

        if ($show->poster_path) {
            JsonLd::setImage('https://image.tmdb.org/t/p/w500'.$show->poster_path);
        }

        if ($show->first_air_date) {
            JsonLd::addValue('startDate', $show->first_air_date->format('Y-m-d'));
        }

        if ($show->vote_average > 0) {
            JsonLd::addValue('aggregateRating', [
                '@type'       => 'AggregateRating',
                'ratingValue' => round($show->vote_average, 1),
                'bestRating'  => 10,
                'worstRating' => 0,
                'ratingCount' => $show->vote_count,
            ]);
        }

        if ($show->number_of_seasons) {
            JsonLd::addValue('numberOfSeasons', $show->number_of_seasons);
        }
    }

    protected function setPersonSeo(Person $person): void
    {
        $description = $person->biography
            ?: "Découvrez la filmographie et la biographie de {$person->name} sur Zone Ciné.";

        $image = $person->profile_path
            ? 'https://image.tmdb.org/t/p/w500'.$person->profile_path
            : null;

        $this->setSeo($person->name, $description, $image, 'profile');

        JsonLd::setType('Person');
        JsonLd::setTitle($person->name);
        JsonLd::setDescription(mb_strimwidth($description, 0, 300, '…'));
        JsonLd::setUrl('current');

        if ($person->profile_path) {
            JsonLd::setImage('https://image.tmdb.org/t/p/w500'.$person->profile_path);
        }

        if ($person->birthday) {
            JsonLd::addValue('birthDate', $person->birthday);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\TvShow;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $this->setSeo(
            'Films & Séries en streaming',
            'Catalogue complet de films et séries en français — streaming, bandes-annonces et fiches détaillées.',
        );

        $nowPlaying = Movie::query()
            ->whereNotNull('release_date')
            ->where('release_date', '<=', now())
            ->orderByDesc('popularity')
            ->limit(10)
            ->get();

        $popularShows = TvShow::query()
            ->orderByDesc('popularity')
            ->limit(10)
            ->get();

        return view('home', compact('nowPlaying', 'popularShows'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\TvShow;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $nowPlaying = Movie::query()
            ->whereNotNull('release_date')
            ->where('release_date', '<=', now())
            ->orderByDesc('popularity')
            ->limit(10)
            ->get();

        $upcoming = Movie::query()
            ->where('release_date', '>', now())
            ->orderBy('release_date')
            ->limit(6)
            ->get();

        $popularShows = TvShow::query()
            ->orderByDesc('popularity')
            ->limit(10)
            ->get();

        return view('home', compact('nowPlaying', 'upcoming', 'popularShows'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\View\View;

class GenreController extends Controller
{
    public function movies(string $slug): View
    {
        $genre = Genre::where('slug', $slug)->firstOrFail();

        $this->setSeo(
            "Films {$genre->name}",
            "Tous les films du genre {$genre->name} — classés par popularité sur Zone Ciné.",
        );

        $movies = $genre->movies()
            ->orderByDesc('popularity')
            ->paginate(24);

        return view('genres.movies', compact('genre', 'movies'));
    }

    public function tvShows(string $slug): View
    {
        $genre = Genre::where('slug', $slug)->firstOrFail();

        $this->setSeo(
            "Séries {$genre->name}",
            "Toutes les séries du genre {$genre->name} — classées par popularité sur Zone Ciné.",
        );

        $shows = $genre->tvShows()
            ->orderByDesc('popularity')
            ->paginate(24);

        return view('genres.tv', compact('genre', 'shows'));
    }
}

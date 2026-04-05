<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\TvShow;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(Request $request): View
    {
        $query = trim($request->get('q', ''));

        $movies  = collect();
        $tvShows = collect();

        if (strlen($query) >= 2) {
            $movies = Movie::query()
                ->where('title', 'like', "%{$query}%")
                ->orWhere('original_title', 'like', "%{$query}%")
                ->orderByDesc('popularity')
                ->limit(12)
                ->get();

            $tvShows = TvShow::query()
                ->where('name', 'like', "%{$query}%")
                ->orWhere('original_name', 'like', "%{$query}%")
                ->orderByDesc('popularity')
                ->limit(12)
                ->get();
        }

        return view('search.index', compact('query', 'movies', 'tvShows'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MovieController extends Controller
{
    public function index(Request $request): View
    {
        $movies = Movie::query()
            ->with('genres')
            ->when($request->genre, fn ($q) => $q->whereHas('genres', fn ($q) => $q->where('slug', $request->genre)))
            ->when($request->annee, fn ($q) => $q->whereYear('release_date', $request->annee))
            ->when($request->langue, fn ($q) => $q->where('original_language', $request->langue))
            ->when($request->tri === 'date', fn ($q) => $q->orderByDesc('release_date'))
            ->when($request->tri === 'note', fn ($q) => $q->orderByDesc('vote_average'))
            ->when(! $request->tri, fn ($q) => $q->orderByDesc('popularity'))
            ->paginate(24)
            ->withQueryString();

        return view('movies.index', compact('movies'));
    }

    public function show(string $slug): View
    {
        $movie = Movie::query()
            ->with(['genres', 'directors', 'cast', 'watchProviders', 'videos'])
            ->where('slug', $slug)
            ->firstOrFail();

        return view('movies.show', compact('movie'));
    }
}

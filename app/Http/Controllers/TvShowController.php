<?php

namespace App\Http\Controllers;

use App\Models\TvShow;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TvShowController extends Controller
{
    public function index(Request $request): View
    {
        $shows = TvShow::query()
            ->with('genres')
            ->when($request->genre, fn ($q) => $q->whereHas('genres', fn ($q) => $q->where('slug', $request->genre)))
            ->when($request->statut, fn ($q) => $q->where('status', $request->statut))
            ->when($request->langue, fn ($q) => $q->where('original_language', $request->langue))
            ->when($request->tri === 'date', fn ($q) => $q->orderByDesc('first_air_date'))
            ->when($request->tri === 'note', fn ($q) => $q->orderByDesc('vote_average'))
            ->when(! $request->tri, fn ($q) => $q->orderByDesc('popularity'))
            ->paginate(24)
            ->withQueryString();

        return view('tv.index', compact('shows'));
    }

    public function show(string $slug): View
    {
        $show = TvShow::query()
            ->with(['genres', 'cast', 'crew', 'watchProviders', 'videos', 'seasons.episodes'])
            ->where('slug', $slug)
            ->firstOrFail();

        return view('tv.show', compact('show'));
    }
}

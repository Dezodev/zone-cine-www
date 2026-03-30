<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\View\View;

class PersonController extends Controller
{
    public function show(string $slug): View
    {
        $person = Person::query()
            ->with([
                'movies' => fn ($q) => $q->orderByDesc('release_date')->limit(20),
                'tvShows' => fn ($q) => $q->orderByDesc('first_air_date')->limit(20),
            ])
            ->where('slug', $slug)
            ->firstOrFail();

        return view('people.show', compact('person'));
    }
}

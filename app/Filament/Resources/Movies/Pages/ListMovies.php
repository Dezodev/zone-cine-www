<?php

namespace App\Filament\Resources\Movies\Pages;

use App\Filament\Resources\Movies\MovieResource;
use App\Models\Movie;
use Filament\Resources\Pages\Page;
use Livewire\WithPagination;

class ListMovies extends Page
{
    use WithPagination;

    protected static string $resource = MovieResource::class;

    protected string $view = 'filament.resources.movies.list-movies';

    public string $search = '';

    public ?bool $filterHidden = null;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilterHidden(): void
    {
        $this->resetPage();
    }

    public function getEditUrl(int $id): string
    {
        return MovieResource::getUrl('edit', ['record' => $id]);
    }

    public function getMovies()
    {
        return Movie::withoutGlobalScope('visible')
            ->when($this->search, fn ($q) => $q->where('title', 'like', '%' . $this->search . '%'))
            ->when($this->filterHidden !== null, fn ($q) => $q->where('hidden', $this->filterHidden))
            ->orderByDesc('popularity')
            ->paginate(60);
    }
}

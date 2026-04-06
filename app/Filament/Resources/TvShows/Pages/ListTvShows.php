<?php

namespace App\Filament\Resources\TvShows\Pages;

use App\Filament\Resources\TvShows\TvShowResource;
use App\Models\TvShow;
use Filament\Resources\Pages\Page;
use Livewire\WithPagination;

class ListTvShows extends Page
{
    use WithPagination;

    protected static string $resource = TvShowResource::class;

    protected string $view = 'filament.resources.tv-shows.list-tv-shows';

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
        return TvShowResource::getUrl('edit', ['record' => $id]);
    }

    public function getTvShows()
    {
        return TvShow::withoutGlobalScope('visible')
            ->when($this->search, fn ($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->when($this->filterHidden !== null, fn ($q) => $q->where('hidden', $this->filterHidden))
            ->orderByDesc('popularity')
            ->paginate(60);
    }
}

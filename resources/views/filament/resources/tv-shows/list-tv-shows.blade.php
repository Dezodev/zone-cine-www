<x-filament-panels::page>
    <div class="space-y-4">
        {{-- Barre de recherche et filtres --}}
        <div class="flex flex-wrap gap-3 items-center">
            <div class="flex-1 min-w-48">
                <x-filament::input.wrapper>
                    <x-filament::input
                        type="search"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Rechercher une série…"
                    />
                </x-filament::input.wrapper>
            </div>
            <div>
                <x-filament::input.wrapper>
                    <x-filament::input.select wire:model.live="filterHidden">
                        <option value="">Toutes</option>
                        <option value="0">Visibles</option>
                        <option value="1">Masquées</option>
                    </x-filament::input.select>
                </x-filament::input.wrapper>
            </div>
        </div>

        @php $tvShows = $this->getTvShows(); @endphp

        {{-- Grille de pochettes --}}
        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-7 xl:grid-cols-9 gap-3">
            @foreach ($tvShows as $tvShow)
                <a
                    href="{{ $this->getEditUrl($tvShow->id) }}"
                    class="group relative block rounded overflow-hidden bg-gray-100 dark:bg-gray-800 aspect-[2/3]"
                    title="{{ $tvShow->name }}"
                >
                    @if ($tvShow->poster_path)
                        <img
                            src="https://image.tmdb.org/t/p/w185{{ $tvShow->poster_path }}"
                            alt="{{ $tvShow->name }}"
                            class="w-full h-full object-cover transition-opacity group-hover:opacity-75"
                            loading="lazy"
                        >
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs text-center p-2">
                            {{ $tvShow->name }}
                        </div>
                    @endif

                    {{-- Badge masqué --}}
                    @if ($tvShow->hidden)
                        <div class="absolute top-1 right-1 bg-red-500 rounded-full w-3 h-3" title="Masqué"></div>
                    @endif

                    {{-- Overlay titre au hover --}}
                    <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/80 to-transparent p-2 translate-y-full group-hover:translate-y-0 transition-transform">
                        <p class="text-white text-xs font-medium leading-tight line-clamp-2">{{ $tvShow->name }}</p>
                        @if ($tvShow->first_air_date)
                            <p class="text-gray-300 text-xs">{{ $tvShow->first_air_date->format('Y') }}</p>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if ($tvShows->hasPages())
            <div class="flex items-center justify-between">
                @if ($tvShows->onFirstPage())
                    <x-filament::button disabled color="gray">{!! __('pagination.previous') !!}</x-filament::button>
                @else
                    <x-filament::button tag="a" href="{{ $tvShows->previousPageUrl() }}" color="gray">{!! __('pagination.previous') !!}</x-filament::button>
                @endif

                <span class="text-sm text-gray-500">Page {{ $tvShows->currentPage() }} / {{ $tvShows->lastPage() }}</span>

                @if ($tvShows->hasMorePages())
                    <x-filament::button tag="a" href="{{ $tvShows->nextPageUrl() }}" color="gray">{!! __('pagination.next') !!}</x-filament::button>
                @else
                    <x-filament::button disabled color="gray">{!! __('pagination.next') !!}</x-filament::button>
                @endif
            </div>
        @endif
    </div>
</x-filament-panels::page>

@extends('layouts.app')


@section('content')
  <section class="section">
    <div class="section__inner">

      <div class="section__header">
        <h1 class="section__title">Recherche</h1>
      </div>

      <form class="search-form" method="GET" action="{{ route('search') }}" role="search">
        <div class="search-form__field">
          <x-gmsi-o-search class="search-form__icon h-5 w-5" />
          <input
            type="search"
            name="q"
            value="{{ $query }}"
            placeholder="Titre d'un film ou d'une série…"
            class="search-form__input"
            autofocus
            autocomplete="off"
          >
          @if ($query)
            <a href="{{ route('search') }}" class="search-form__clear" aria-label="Effacer">
              <x-gmsi-o-close class="h-4 w-4" />
            </a>
          @endif
        </div>
      </form>

      @if ($query && strlen($query) < 2)
        <p class="search-empty">Saisissez au moins 2 caractères.</p>

      @elseif ($query && $movies->isEmpty() && $tvShows->isEmpty())
        <p class="search-empty">Aucun résultat pour « {{ $query }} ».</p>

      @elseif ($query)

        @if ($movies->isNotEmpty())
          <div class="search-section">
            <h2 class="search-section__title">
              Films
              <span class="search-section__count">{{ $movies->count() }}</span>
            </h2>
            <div class="media-grid media-grid--wide">
              @foreach ($movies as $movie)
                <x-media-card :media="$movie" />
              @endforeach
            </div>
          </div>
        @endif

        @if ($tvShows->isNotEmpty())
          <div class="search-section">
            <h2 class="search-section__title">
              Séries
              <span class="search-section__count">{{ $tvShows->count() }}</span>
            </h2>
            <div class="media-grid media-grid--wide">
              @foreach ($tvShows as $tvShow)
                <x-media-card :media="$tvShow" />
              @endforeach
            </div>
          </div>
        @endif

      @endif

    </div>
  </section>
@endsection

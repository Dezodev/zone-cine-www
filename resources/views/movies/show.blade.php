@extends('layouts.app')


@section('content')
  <article class="media-detail">

    {{-- Backdrop --}}
    <div class="media-detail__backdrop-wrap">
      @if ($movie->backdrop_path)
        <img
          class="media-detail__backdrop"
          src="https://image.tmdb.org/t/p/w1280{{ $movie->backdrop_path }}"
          alt="{{ $movie->title }}"
        >
      @endif
      <div class="media-detail__backdrop-overlay"></div>
    </div>

    <div class="media-detail__main">

      {{-- En-tête : affiche + titre --}}
      <div class="media-detail__layout">

        <div class="media-detail__poster-wrap">
          @if ($movie->poster_path)
            <img
              class="media-detail__poster"
              src="https://image.tmdb.org/t/p/w500{{ $movie->poster_path }}"
              alt="{{ $movie->title }}"
            >
          @else
            <div class="media-detail__poster--placeholder">
              <x-gmsi-o-movie class="h-16 w-16" />
            </div>
          @endif
        </div>

        <div class="media-detail__info">
          <h1 class="media-detail__title">{{ $movie->title }}</h1>
          @if ($movie->original_title !== $movie->title)
            <p class="media-detail__original-title">{{ $movie->original_title }}</p>
          @endif

          <div class="media-detail__meta">
            @if ($movie->release_date)
              <span>{{ $movie->release_date->format('d/m/Y') }}</span>
              <span class="media-detail__meta-separator">·</span>
            @endif
            @if ($movie->runtime)
              <span>{{ intdiv($movie->runtime, 60) }}h{{ str_pad($movie->runtime % 60, 2, '0', STR_PAD_LEFT) }}</span>
              <span class="media-detail__meta-separator">·</span>
            @endif
            @if ($movie->original_language)
              <span class="uppercase">{{ $movie->original_language }}</span>
              <span class="media-detail__meta-separator">·</span>
            @endif
            @if ($movie->vote_average > 0)
              <span class="rating-badge {{ $movie->vote_average >= 7 ? 'rating-badge--high' : ($movie->vote_average >= 5 ? 'rating-badge--mid' : 'rating-badge--low') }}">
                ★ {{ number_format($movie->vote_average, 1) }}
              </span>
              <span class="text-base-content/30 text-xs">{{ number_format($movie->vote_count) }} votes</span>
            @endif
          </div>

          @if ($movie->genres->isNotEmpty())
            <div class="media-detail__genres">
              @foreach ($movie->genres as $genre)
                <a href="{{ route('genres.movies', $genre->slug) }}" class="genre-badge">
                  {{ $genre->name }}
                </a>
              @endforeach
            </div>
          @endif
        </div>
      </div>

      {{-- Corps 2 colonnes --}}
      <div class="media-detail__body">

        {{-- Colonne principale : synopsis + bande-annonce --}}
        <div class="media-detail__primary">
          @if ($movie->tagline)
            <p class="media-detail__tagline">« {{ $movie->tagline }} »</p>
          @endif
          @if ($movie->overview)
            <p class="media-detail__overview">{{ $movie->overview }}</p>
          @endif

          @if ($movie->trailer)
            <div class="media-detail__trailer">
              <lite-youtube
                videoid="{{ $movie->trailer->youtube_key }}"
                nocookie
                params="rel=0"
              ></lite-youtube>
            </div>
          @endif
        </div>

        {{-- Colonne secondaire : plateformes + infos --}}
        <aside class="media-detail__secondary">

          <div class="media-detail__secondary-block">
            <h2 class="media-detail__section-title">Où regarder en France</h2>
            <x-streaming-providers :providers="$movie->watchProviders" />
          </div>

          <div class="media-detail__secondary-block">
            <h2 class="media-detail__section-title">Fiche technique</h2>
            <dl class="media-detail__facts">
              @if ($movie->directors->isNotEmpty())
                <div class="media-detail__fact">
                  <dt>Réalisation</dt>
                  <dd>{{ $movie->directors->pluck('name')->join(', ') }}</dd>
                </div>
              @endif
              @if ($movie->original_language)
                <div class="media-detail__fact">
                  <dt>Langue originale</dt>
                  <dd>{{ \Locale::getDisplayLanguage($movie->original_language, 'fr') }}</dd>
                </div>
              @endif
              @if ($movie->status)
                <div class="media-detail__fact">
                  <dt>Statut</dt>
                  <dd>{{ \App\Enums\MovieStatus::tryFrom($movie->status)?->label() ?? $movie->status }}</dd>
                </div>
              @endif
              @if ($movie->budget > 0 || $movie->revenue > 0)
                <div class="media-detail__facts-row">
                  @if ($movie->budget > 0)
                    <div class="media-detail__fact">
                      <dt>Budget</dt>
                      <dd>${{ number_format($movie->budget / 1_000_000, 1) }}M</dd>
                    </div>
                  @endif
                  @if ($movie->revenue > 0)
                    <div class="media-detail__fact">
                      <dt>Recettes</dt>
                      <dd>${{ number_format($movie->revenue / 1_000_000, 1) }}M</dd>
                    </div>
                  @endif
                </div>
              @endif
            </dl>
          </div>

        </aside>
      </div>

      {{-- Casting (pleine largeur) --}}
      @if ($movie->cast->isNotEmpty())
        <div class="media-detail__section">
          <h2 class="media-detail__section-title">Casting</h2>
          <div class="cast-scroll">
            @foreach ($movie->cast->take(20) as $person)
              <x-person-card :person="$person" :role="$person->pivot->character" />
            @endforeach
          </div>
        </div>
      @endif

    </div>
  </article>
@endsection
